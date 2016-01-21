package store

import (
	"github.com/mattermost/platform/model"
// "github.com/mattermost/platform/utils"
)

type SqlTabStore struct {
	*SqlStore
}

func NewSqlTabStore(sqlStore *SqlStore) TabStore {
	s := &SqlTabStore{sqlStore}

	for _, db := range sqlStore.GetAllConns() {

		table := db.AddTableWithName(model.Tab{}, "Tab").SetKeys(false, "Id")
		table.ColMap("Id").SetMaxSize(26)
		table.ColMap("TabTemplate").SetMaxSize(256)
		table.ColMap("RoleName").SetMaxSize(256)
		table.ColMap("CreatedBy").SetMaxSize(128)
		table.ColMap("Name").SetMaxSize(64).SetUnique(true)
		table.SetUniqueTogether("RoleName", "TabTemplate")
	}
	return s
}

func (s SqlTabStore) UpgradeSchemaIfNeeded() {
}

func (s SqlTabStore) Save(tab *model.Tab) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		if len(tab.Id) > 0 {
			result.Err = model.NewAppError("SqlTabStore.Save",
				"Must call update for exisiting Tab", "id="+ tab.Id)
			storeChannel <- result
			close(storeChannel)
			return
		}

		tab.PreSave()

		if err := s.GetMaster().Insert(tab); err != nil {
			if IsUniqueConstraintError(err.Error(), "Name", "tab_name_key") {
				result.Err = model.NewAppError("SqlTemplateStore.Save", "A Tab with this name already exists", "id="+ tab.Id+", "+err.Error())
			} else {
				result.Err = model.NewAppError("SqlTemplateStore.Save", "We couldn't save the tab", "id="+ tab.Id+", "+err.Error())
			}
		} else {
			result.Data = tab
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlTabStore) Update(tab *model.Tab) StoreChannel {

	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		tab.PreUpdate()

		if oldResult, err := s.GetMaster().Get(model.Tab{}, tab.Id); err != nil {
			result.Err = model.NewAppError("SqlTabStore.Update", "We encountered an error finding the tab", "id="+ tab.Id+", "+err.Error())
		} else if oldResult == nil {
			result.Err = model.NewAppError("SqlTabStore.Update", "We couldn't find the existing tab to update", "id="+ tab.Id)
		} else {
			oldTab := oldResult.(*model.Tab)
			tab.CreateAt = oldTab.CreateAt
			tab.UpdateAt = model.GetMillis()
			tab.Name = oldTab.Name
			tab.RoleName = oldTab.RoleName
			tab.CreatedBy = oldTab.CreatedBy

			if count, err := s.GetMaster().Update(tab); err != nil {
				result.Err = model.NewAppError("SqlTabStore.Update", "We encountered an error updating the tab", "id="+ tab.Id+", "+err.Error())
			} else if count != 1 {
				result.Err = model.NewAppError("SqlTabStore.Update", "We couldn't update the tab", "id="+ tab.Id)
			} else {
				result.Data = tab
			}
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlTabStore) GetByName(name string) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		tab := model.Tab{}

		if err := s.GetReplica().SelectOne(&tab, "SELECT * FROM Tab WHERE Name = :Name", map[string]interface{}{"Name": name}); err != nil {
			result.Err = model.NewAppError("SqlTabStore.GetByName", "We couldn't find the existing tab", "name="+name+", "+err.Error())
		}

		result.Data = &tab

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlTabStore) GetAll() StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}
		var tabs model.Tabs

		if _, err := s.GetReplica().Select(&tabs, "SELECT * FROM Tab"); err != nil {
			result.Err = model.NewAppError("SqlTabStore.GetAll", "We could not get all tabs", err.Error())
		}

		result.Data = tabs
		storeChannel <- result
		close(storeChannel)
	}()
	return storeChannel
}

