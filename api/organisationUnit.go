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

func InitOrganisationUnit(r *mux.Router) {
	l4g.Debug("Initializing Organisation Unit api routes")

	sr := r.PathPrefix("/organisationUnit").Subrouter()
	sr.Handle("/create", ApiAppHandler(createOrganisationUnit)).Methods("POST")
	sr.Handle("/findByName", ApiAppHandler(findOrganisationUnitByName)).Methods("POST")
}

func createOrganisationUnit(c *Context, w http.ResponseWriter, r *http.Request) {

	organisationUnit := model.OrganisationUnitFromJson(r.Body)
	l4g.Debug(" OrganisationUnit ", r.Body)

	if organisationUnit == nil {
		c.SetInvalidParam("createOrganisationUnit", "organisationUnit")
		return
	}
	if organisationUnit.OrganisationName == "" {
		c.SetInvalidParam("createOrganisationUnit", "organisationUnit")
		return
	}

	if result := <-Srv.Store.Organisation().GetByName(organisationUnit.OrganisationName); result.Err != nil {
		c.Err = result.Err
		l4g.Debug( " *************** " ,result.Data," ***** " ,result.Err )
		return
	}

	if result := <-Srv.Store.OrganisationUnit().Save(organisationUnit); result.Err != nil {
		c.Err = result.Err
		return
	} else {
		organisationUnit = result.Data.(*model.OrganisationUnit)
	}
	w.Write([]byte(organisationUnit.ToJson()))
}

func findOrganisationUnitByName(c *Context, w http.ResponseWriter, r *http.Request) {

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