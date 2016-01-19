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

func InitTabTemplate(r *mux.Router) {
	l4g.Debug("Initializing TAbTemplate api routes")

	sr := r.PathPrefix("/tabtemplate").Subrouter()
	sr.Handle("/create", ApiAppHandler(createTabTemplate)).Methods("POST")
	sr.Handle("/findByName", ApiAppHandler(findTabTemplateByName)).Methods("POST")
	sr.Handle("/track", ApiAppHandler(listTabTemplate)).Methods("POST")
}

func createTabTemplate(c *Context, w http.ResponseWriter, r *http.Request) {

	tabTemplate := model.TabTemplateFromJson(r.Body)
	l4g.Debug(" TabTemplate ", r.Body)

	if tabTemplate == nil {
		c.SetInvalidParam("createTabTemplate", "tabTemplate")
		return
	}
	if tabTemplate.Name == "" {
		c.SetInvalidParam("createTabTemplate", "tabTemplate")
		return
	}

	if result := <-Srv.Store.TabTemplate().Save(tabTemplate); result.Err != nil {
		c.Err = result.Err
		return
	} else {
		tabTemplate = result.Data.(*model.TabTemplate)
	}
	w.Write([]byte(tabTemplate.ToJson()))
}

func findTabTemplateByName(c *Context, w http.ResponseWriter, r *http.Request) {

	tabTemplate := model.TabTemplateFromJson(r.Body)
	if tabTemplate == nil {
		c.SetInvalidParam("findTabTemplateByName", "tabTemplate")
		return
	}

	if result := <-Srv.Store.TabTemplate().GetByName(tabTemplate.Name); result.Err != nil {
		w.Write([]byte(result.Err.ToJson()))
		c.Err = result.Err
		return
	} else {
		tabTemplate = result.Data.(*model.TabTemplate)
	}
	w.Write([]byte(tabTemplate.ToJson()))
}

func listTabTemplate(c *Context, w http.ResponseWriter, r *http.Request) {

	//var organisationUnits []*model.OrganisationUnit
	tabTemplate := model.TabTemplateFromJson(r.Body)
	if tabTemplate == nil {
		c.SetInvalidParam("listTabTemplate", "tabTemplate")
		return
	}

	if result := <-Srv.Store.TabTemplate().GetAll(); result.Err != nil {
		w.Write([]byte(result.Err.ToJson()))
		c.Err = result.Err
		return
	} else {
		w.Write([]byte(result.Data.(model.TabTemplates).ToJson()))
	}
}