package model

import (
	"encoding/json"
	"io"
)

type Role struct {
	Id               string `json:"id"`
	CreateAt         int64  `json:"create_at"`
	UpdateAt         int64  `json:"update_at"`
	DeleteAt         int64  `json:"delete_at"`
	OrganisationName string `json:"organisationName"`
	RoleName	     string `json:"role_name"`
}

// ToJson convert a OrganisationUnit to a json string
func (o *Role) ToJson() string {
	b, err := json.Marshal(o)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func RoleFromJson(data io.Reader) *Role {
	decoder := json.NewDecoder(data)
	var o Role
	err := decoder.Decode(&o)
	if err == nil {
		return &o
	} else {
		return nil
	}
}

func RoleMapToJson(u map[string]*Role) string {
	b, err := json.Marshal(u)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func RoleMapFromJson(data io.Reader) map[string]*Role {
	decoder := json.NewDecoder(data)
	var role map[string]*Role
	err := decoder.Decode(&role)
	if err == nil {
		return role
	} else {
		return nil
	}
}

func (o *Role) Etag() string {
	return Etag(o.Id, o.UpdateAt)
}

func (o *Role) PreSave() {
	if o.Id == "" {
		o.Id = NewId()
	}

	o.CreateAt = GetMillis()
	o.UpdateAt = o.CreateAt
}

func (o *Role) PreUpdate() {
	o.UpdateAt = GetMillis()
}