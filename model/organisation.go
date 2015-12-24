package model

import (
	"encoding/json"
	"io"
)

type Organisation struct {
	Id               string `json:"id"`
	CreateAt         int64  `json:"create_at"`
	UpdateAt         int64  `json:"update_at"`
	DeleteAt         int64  `json:"delete_at"`
	Name             string `json:"name"`
	CreatedBy	 string `json:"createdBy"`
	Email            string `json:"email"`
}

func OrganisationFromJson(data io.Reader) *Organisation {
	decoder := json.NewDecoder(data)
	var o Organisation
	err := decoder.Decode(&o)
	if err == nil {
		return &o
	} else {
		return nil
	}
}

// ToJson convert a Organisation to a json string
func (o *Organisation) ToJson() string {
	b, err := json.Marshal(o)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func OrganisationMapToJson(u map[string]*Organisation) string {
	b, err := json.Marshal(u)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func OrganisationMapFromJson(data io.Reader) map[string]*Organisation {
	decoder := json.NewDecoder(data)
	var organisation map[string]*Organisation
	err := decoder.Decode(&organisation)
	if err == nil {
		return organisation
	} else {
		return nil
	}
}

func (o *Organisation) Etag() string {
	return Etag(o.Id, o.UpdateAt)
}

func (o *Organisation) PreSave() {
	if o.Id == "" {
		o.Id = NewId()
	}

	o.CreateAt = GetMillis()
	o.UpdateAt = o.CreateAt
}

func (o *Organisation) PreUpdate() {
	o.UpdateAt = GetMillis()
}