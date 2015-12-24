// Copyright (c) 2015 Mattermost, Inc. All Rights Reserved.
// See License.txt for license information.

package api

import (
	l4g "code.google.com/p/log4go"
	"github.com/gorilla/mux"
	"github.com/mattermost/platform/model"
	_ "image/gif"
	_ "image/jpeg"
	"net/http"
)

func InitOrganisation(r *mux.Router) {
	l4g.Debug("Initializing Organisation api routes")
	sr := r.PathPrefix("/organisation").Subrouter()
	sr.Handle("/create", ApiAppHandler(createOrganisation)).Methods("POST")
	sr.Handle("/findByName", ApiAppHandler(findOrganisationByName)).Methods("POST")
}

func createOrganisation(c *Context, w http.ResponseWriter, r *http.Request) {

	organisation := model.OrganisationFromJson(r.Body)
	if organisation == nil {
		c.SetInvalidParam("createOrganisation", "name")
		return
	}

	if result := <-Srv.Store.Organisation().Save(organisation); result.Err != nil {
		c.Err = result.Err
		return
	} else {
		organisation = result.Data.(*model.Organisation)
	}
	w.Write([]byte(organisation.ToJson()))
}

func findOrganisationByName(c *Context, w http.ResponseWriter, r *http.Request) {

	organisation := model.OrganisationFromJson(r.Body)
	if organisation == nil {
		c.SetInvalidParam("findOrganisationByName", "organisation")
		return
	}

	if result := <-Srv.Store.Organisation().GetByName(organisation.Name); result.Err != nil {
		w.Write([]byte(result.Err.ToJson()))
		c.Err = result.Err
		return
	} else {
		organisation = result.Data.(*model.Organisation)
	}
	w.Write([]byte(organisation.ToJson()))
}