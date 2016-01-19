package store

import (
	"github.com/mattermost/platform/model"
// "github.com/mattermost/platform/utils"
)

type SqlTabTemplateStore struct {
	*SqlStore
}

func NewSqlTabTemplateStore(sqlStore *SqlStore) TabTemplateStore {
	s := &SqlTabTemplateStore{sqlStore}

	for _, db := range sqlStore.GetAllConns() {

		table := db.AddTableWithName(model.TabTemplate{}, "TabTemplate").SetKeys(false, "Id")
		table.ColMap("Id").SetMaxSize(26)
		table.ColMap("Name").SetMaxSize(64).SetUnique(true)
		table.ColMap("Template").SetMaxSize(256)
		table.ColMap("CreatedBy").SetMaxSize(128)
	}
	return s
}

func (s SqlTabTemplateStore) UpgradeSchemaIfNeeded() {
}

func (s SqlTabTemplateStore) Save(tabTemplate *model.TabTemplate) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		if len(tabTemplate.Id) > 0 {
			result.Err = model.NewAppError("SqlTabTemplateStore.Save",
				"Must call update for exisiting TabTemplate", "id="+ tabTemplate.Id)
			storeChannel <- result
			close(storeChannel)
			return
		}

		tabTemplate.PreSave()

		if err := s.GetMaster().Insert(tabTemplate); err != nil {
			if IsUniqueConstraintError(err.Error(), "Name", "template_name_key") {
				result.Err = model.NewAppError("SqlTemplateStore.Save", "A Template with this name already exists", "id="+ tabTemplate.Id+", "+err.Error())
			} else {
				result.Err = model.NewAppError("SqlTemplateStore.Save", "We couldn't save the template", "id="+ tabTemplate.Id+", "+err.Error())
			}
		} else {
			result.Data = tabTemplate
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlTabTemplateStore) Update(tabTemplate *model.TabTemplate) StoreChannel {

	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		tabTemplate.PreUpdate()

		if oldResult, err := s.GetMaster().Get(model.TabTemplate{}, tabTemplate.Id); err != nil {
			result.Err = model.NewAppError("SqlTabTemplateStore.Update", "We encountered an error finding the tabTemplate", "id="+ tabTemplate.Id+", "+err.Error())
		} else if oldResult == nil {
			result.Err = model.NewAppError("SqlTabTemplateStore.Update", "We couldn't find the existing tabTemplate to update", "id="+ tabTemplate.Id)
		} else {
			oldTabTemplate := oldResult.(*model.TabTemplate)
			tabTemplate.CreateAt = oldTabTemplate.CreateAt
			tabTemplate.UpdateAt = model.GetMillis()
			tabTemplate.Name = oldTabTemplate.Name
			tabTemplate.CreatedBy = oldTabTemplate.CreatedBy

			if count, err := s.GetMaster().Update(tabTemplate); err != nil {
				result.Err = model.NewAppError("SqlTabTemplateStore.Update", "We encountered an error updating the tabTemplate", "id="+ tabTemplate.Id+", "+err.Error())
			} else if count != 1 {
				result.Err = model.NewAppError("SqlTabTemplateStore.Update", "We couldn't update the tabTemplate", "id="+ tabTemplate.Id)
			} else {
				result.Data = tabTemplate
			}
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlTabTemplateStore) GetByName(name string) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		tabTemplate := model.TabTemplate{}

		if err := s.GetReplica().SelectOne(&tabTemplate, "SELECT * FROM TabTemplate WHERE Name = :Name", map[string]interface{}{"Name": name}); err != nil {
			result.Err = model.NewAppError("SqlTabTemplateStore.GetByName", "We couldn't find the existing tabtemplate", "name="+name+", "+err.Error())
		}

		result.Data = &tabTemplate

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlTabTemplateStore) GetAll() StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}
		var tabTemplates model.TabTemplates

		if _, err := s.GetReplica().Select(&tabTemplates, "SELECT * FROM TabTemplate"); err != nil {
			result.Err = model.NewAppError("SqlTabTemplateStore.GetAll", "We could not get all template", err.Error())
		}

		result.Data = tabTemplates
		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

