package model

import (
	"encoding/json"
	"io"
)

type OrganisationUnit struct {
	Id               string `json:"id"`
	CreateAt         int64  `json:"create_at"`
	UpdateAt         int64  `json:"update_at"`
	DeleteAt         int64  `json:"delete_at"`
	CreatedBy	 string `json:"createdBy"`
	Organisation     string `json:"organisation"`
	OrganisationUnit string `json:"organisation_unit"`
}

// ToJson convert a OrganisationUnit to a json string
func (o *OrganisationUnit) ToJson() string {
	b, err := json.Marshal(o)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func OrganisationUnitFromJson(data io.Reader) *OrganisationUnit {
	decoder := json.NewDecoder(data)
	var o OrganisationUnit
	err := decoder.Decode(&o)
	if err == nil {
		return &o
	} else {
		return nil
	}
}

func OrganisationUnitMapToJson(u map[string]*OrganisationUnit) string {
	b, err := json.Marshal(u)
	if err != nil {
		return ""
	} else {
		return string(b)
	}
}

func OrganisationUnitMapFromJson(data io.Reader) map[string]*OrganisationUnit {
	decoder := json.NewDecoder(data)
	var organisationUnit map[string]*OrganisationUnit
	err := decoder.Decode(&organisationUnit)
	if err == nil {
		return organisationUnit
	} else {
		return nil
	}
}

func (o *OrganisationUnit) Etag() string {
	return Etag(o.Id, o.UpdateAt)
}

func (o *OrganisationUnit) PreSave() {
	if o.Id == "" {
		o.Id = NewId()
	}

	o.CreateAt = GetMillis()
	o.UpdateAt = o.CreateAt
}

func (o *OrganisationUnit) PreUpdate() {
	o.UpdateAt = GetMillis()
}