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
//	"database/sql"
)

func InitRole(r *mux.Router) {
	l4g.Debug("Initializing Role api routes")

	sr := r.PathPrefix("/organisationRole").Subrouter()
	sr.Handle("/create", ApiAppHandler(createRole)).Methods("POST")
	sr.Handle("/find", ApiAppHandler(findRole)).Methods("POST")
}

func createRole(c *Context, w http.ResponseWriter, r *http.Request) {

	role := model.RoleFromJson(r.Body)
	l4g.Debug(" Role ", r.Body)

	if role == nil {
		c.SetInvalidParam("createRole", "role")
		return
	}
	/*
	this constraint  will  be bypassed
	if role.OrganisationUnit == "" {
		c.SetInvalidParam("createOrganisationUnit", "organisationUnit")
		return
	}*/

	if result := <-Srv.Store.Role().Save(role); result.Err != nil {
		c.Err = result.Err
		return
	} else {
		role = result.Data.(*model.Role)
	}
	w.Write([]byte(role.ToJson()))
}

func findRole(c *Context, w http.ResponseWriter, r *http.Request) {

	role := model.RoleFromJson(r.Body)
	if role == nil {
		c.SetInvalidParam("findOrganisationByName", "organisation")
		return
	}

	if result := <-Srv.Store.Role().GetRoles(role.OrganisationUnit); result.Err != nil {
		w.Write([]byte(result.Err.ToJson()))
		c.Err = result.Err
		return
	} else {
		role = result.Data.(*model.Role)
	}
	w.Write([]byte(role.ToJson()))
}
