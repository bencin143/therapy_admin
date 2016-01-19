package model

import (
"encoding/json"
"io"
)

type TabTemplates []TabTemplate

func (o TabTemplates) Etag() string {
if len(o) > 0 {
// the first in the list is always the most current
return Etag(o[0].CreateAt)
} else {
return ""
}
}

func (o TabTemplates) ToJson() string {
if b, err := json.Marshal(o); err != nil {
return "[]"
} else {
return string(b)
}
}

func TabTemplatesFromJson(data io.Reader) TabTemplates {
decoder := json.NewDecoder(data)
var o TabTemplates
err := decoder.Decode(&o)
if err == nil {
return o
} else {
return nil
}
}