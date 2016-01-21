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

func InitTab(r *mux.Router) {
	l4g.Debug("Initializing Tab api routes")

	sr := r.PathPrefix("/tab").Subrouter()
	sr.Handle("/create", ApiAppHandler(createTab)).Methods("POST")
	sr.Handle("/findByName", ApiAppHandler(findTabByName)).Methods("POST")
	sr.Handle("/all", ApiAppHandler(listTab)).Methods("POST")
}

func createTab(c *Context, w http.ResponseWriter, r *http.Request) {

	tab := model.TabFromJson(r.Body)
	l4g.Debug(" Tab ", r.Body)

	if tab == nil {
		c.SetInvalidParam("createTab", "tab")
		return
	}
	if tab.Name == "" {
		c.SetInvalidParam("createTab", "tab")
		return
	}

	if result := <-Srv.Store.Tab().Save(tab); result.Err != nil {
		c.Err = result.Err
		return
	} else {
		tab = result.Data.(*model.Tab)
	}
	w.Write([]byte(tab.ToJson()))
}

func findTabByName(c *Context, w http.ResponseWriter, r *http.Request) {

	tab := model.TabFromJson(r.Body)
	if tab == nil {
		c.SetInvalidParam("findTabByName", "tab")
		return
	}

	if result := <-Srv.Store.Tab().GetByName(tab.Name); result.Err != nil {
		w.Write([]byte(result.Err.ToJson()))
		c.Err = result.Err
		return
	} else {
		tab = result.Data.(*model.Tab)
	}
	w.Write([]byte(tab.ToJson()))
}

func listTab(c *Context, w http.ResponseWriter, r *http.Request) {

	tab := model.TabFromJson(r.Body)
	if tab == nil {
		c.SetInvalidParam("listTab", "tab")
		return
	}

	if result := <-Srv.Store.Tab().GetAll(); result.Err != nil {
		w.Write([]byte(result.Err.ToJson()))
		c.Err = result.Err
		return
	} else {
		w.Write([]byte(result.Data.(model.Tabs).ToJson()))
	}
}