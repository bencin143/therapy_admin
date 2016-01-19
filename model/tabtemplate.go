package model

import (
	"encoding/json"
	"io"
)

type TabTemplate struct {
	Id               string `json:"id"`
	CreateAt         int64  `json:"create_at"`
	UpdateAt         int64  `json:"update_at"`
	DeleteAt         int64  `json:"delete_at"`
	Name             string `json:"name"`
	CreatedBy	 string `json:"createdBy"`
	Template	 string `json:template`
}

func TabTemplateFromJson(data io.Reader) *TabTemplate {
	decoder := json.NewDecoder(data)
	var o TabTemplate
	err := decoder.Decode(&o)
	if err == nil {
		return &o
	} else {
		return nil
	}
}

// ToJson convert a TabTemplate to a json string
func (o *TabTemplate) ToJson() string {
	b, err := json.Marshal(o)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func TabTemplateMapToJson(u map[string]*TabTemplate) string {
	b, err := json.Marshal(u)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func TabTemplateMapFromJson(data io.Reader) map[string]*TabTemplate {
	decoder := json.NewDecoder(data)
	var tabTemplate map[string]*TabTemplate
	err := decoder.Decode(&tabTemplate)
	if err == nil {
		return tabTemplate
	} else {
		return nil
	}
}

func (o *TabTemplate) Etag() string {
	return Etag(o.Id, o.UpdateAt)
}

func (o *TabTemplate) PreSave() {
	if o.Id == "" {
		o.Id = NewId()
	}

	o.CreateAt = GetMillis()
	o.UpdateAt = o.CreateAt
}

func (o *TabTemplate) PreUpdate() {
	o.UpdateAt = GetMillis()
}