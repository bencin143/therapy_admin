package model

import (
	"encoding/json"
	"io"
)

type Tab struct {
	Id               string `json:"id"`
	CreateAt         int64  `json:"create_at"`
	UpdateAt         int64  `json:"update_at"`
	DeleteAt         int64  `json:"delete_at"`
	Name             string `json:"name"`
	RoleName	 string `json:"roleName"`
	CreatedBy	 string `json:"createdBy"`
	TabTemplate	 string `json:tabTemplate`
}

func TabFromJson(data io.Reader) *Tab {
	decoder := json.NewDecoder(data)
	var o Tab
	err := decoder.Decode(&o)
	if err == nil {
		return &o
	} else {
		return nil
	}
}

// ToJson convert a TabTemplate to a json string
func (o *Tab) ToJson() string {
	b, err := json.Marshal(o)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func TabMapToJson(u map[string]*Tab) string {
	b, err := json.Marshal(u)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func TabMapFromJson(data io.Reader) map[string]*Tab {
	decoder := json.NewDecoder(data)
	var tab map[string]*Tab
	err := decoder.Decode(&tab)
	if err == nil {
		return tab
	} else {
		return nil
	}
}

func (o *Tab) Etag() string {
	return Etag(o.Id, o.UpdateAt)
}

func (o *Tab) PreSave() {
	if o.Id == "" {
		o.Id = NewId()
	}

	o.CreateAt = GetMillis()
	o.UpdateAt = o.CreateAt
}

func (o *Tab) PreUpdate() {
	o.UpdateAt = GetMillis()
}