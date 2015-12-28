// Copyright (c) 2015 Mattermost, Inc. All Rights Reserved.
// See License.txt for license information.

package model

import (
	"encoding/json"
	"io"
)

type Organisations []Organisation

func (o Organisations) Etag() string {
	if len(o) > 0 {
		// the first in the list is always the most current
		return Etag(o[0].CreateAt)
	} else {
		return ""
	}
}

func (o Organisations) ToJson() string {
	if b, err := json.Marshal(o); err != nil {
		return "[]"
	} else {
		return string(b)
	}
}

func OrganisationsFromJson(data io.Reader) Organisations {
	decoder := json.NewDecoder(data)
	var o Organisations
	err := decoder.Decode(&o)
	if err == nil {
		return o
	} else {
		return nil
	}
}
