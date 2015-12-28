// Copyright (c) 2015 Mattermost, Inc. All Rights Reserved.
// See License.txt for license information.

package model

import (
	"encoding/json"
	"io"
)

type RoleList struct {
	Roles map[string]*Role `json:"roles"`
}

func (o *RoleList) ToJson() string {
	b, err := json.Marshal(o)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func (o *RoleList) MakeNonNil() {
	if o.Roles == nil {
		o.Roles = make(map[string]*Role)
	}

//	for _, v := range o.Roles {
////		v.MakeNonNil()
//	}
}

func (o *RoleList) AddRole(role *Role) {

	if o.Roles == nil {
		o.Roles = make(map[string]*Role)
	}

	o.Roles[role.Id] = role
}

func (o *RoleList) Etag() string {

	id := "0"
	var t int64 = 0

	for _, v := range o.Roles {
		if v.UpdateAt > t {
			t = v.UpdateAt
			id = v.Id
		}
	}

	return Etag(id, t)
}

func RoleListFromJson(data io.Reader) *RoleList {
	decoder := json.NewDecoder(data)
	var o RoleList
	err := decoder.Decode(&o)
	if err == nil {
		return &o
	} else {
		return nil
	}
}
