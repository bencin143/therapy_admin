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

//	sr.Handle("/newimage", ApiUserRequired(uploadProfileImage)).Methods("POST")
	sr.Handle("/me", ApiAppHandler(getMe)).Methods("GET")
	sr.Handle("/status", ApiUserRequiredActivity(getStatuses, false)).Methods("POST")
}

func createOrganisation(c *Context, w http.ResponseWriter, r *http.Request) {


	organisation := model.OrganisationFromJson(r.Body)
	l4g.Error(" Organisation ", organisation , r.Body)

	if organisation == nil {
		c.SetInvalidParam("createOrganisation", "organisation")
		return
	}

	if result := <-Srv.Store.Organisation().GetByName(organisation.Name); result.Err != nil {
		c.Err = result.Err
		return
	} else {
		organisation = result.Data.(*model.Organisation)
	}

//	hash := r.URL.Query().Get("h")

	rOrganisation := CreateOrganisation(c, organisation)
	if c.Err != nil {
		return
	}

	w.Write([]byte(rOrganisation.ToJson()))
}

func CreateOrganisation(c *Context, organisation *model.Organisation) *model.Organisation {

	if result := <-Srv.Store.Organisation().Save(organisation); result.Err != nil {
		c.Err = result.Err
		l4g.Error("Couldn't save the Organisation err=%v", result.Err)
		return nil
	}
	l4g.Error("Organisation savee")
	return nil
}