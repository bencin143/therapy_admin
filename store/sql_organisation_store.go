package store

import (
	"github.com/mattermost/platform/model"
	// "github.com/mattermost/platform/utils"
)

type SqlOrganisationStore struct {
	*SqlStore
}

func NewSqlOrganisationStore(sqlStore *SqlStore) OrganisationStore {
	s := &SqlOrganisationStore{sqlStore}

	for _, db := range sqlStore.GetAllConns() {
		table := db.AddTableWithName(model.Organisation{}, "Organisation").SetKeys(false, "Id")
		table.ColMap("Id").SetMaxSize(26)
		table.ColMap("Name").SetMaxSize(64).SetUnique(true)
		table.ColMap("Email").SetMaxSize(128)
		table.ColMap("CreatedBy").SetMaxSize(128)
	}
	return s
}

func (s SqlOrganisationStore) UpgradeSchemaIfNeeded() {
}

func (s SqlOrganisationStore) Save(organisation *model.Organisation) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		if len(organisation.Id) > 0 {
			result.Err = model.NewAppError("SqlOrganisationStore.Save",
				"Must call update for exisiting organisation", "id="+ organisation.Id)
			storeChannel <- result
			close(storeChannel)
			return
		}

		organisation.PreSave()

		if err := s.GetMaster().Insert(organisation); err != nil {
			if IsUniqueConstraintError(err.Error(), "Name", "organisation_name_key") {
				result.Err = model.NewAppError("SqlOrganisationStore.Save", "A Organisation with that domain already exists", "id="+ organisation.Id+", "+err.Error())
			} else {
				result.Err = model.NewAppError("SqlOrganisationStore.Save", "We couldn't save the team", "id="+ organisation.Id+", "+err.Error())
			}
		} else {
			result.Data = organisation
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlOrganisationStore) Update(organisation *model.Organisation) StoreChannel {

	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		organisation.PreUpdate()

		if oldResult, err := s.GetMaster().Get(model.Organisation{}, organisation.Id); err != nil {
			result.Err = model.NewAppError("SqlOrganisationStore.Update", "We encountered an error finding the organisation", "id="+ organisation.Id+", "+err.Error())
		} else if oldResult == nil {
			result.Err = model.NewAppError("SqlOrganisationStore.Update", "We couldn't find the existing organisation to update", "id="+ organisation.Id)
		} else {
			oldOrganisation := oldResult.(*model.Organisation)
			organisation.CreateAt = oldOrganisation.CreateAt
			organisation.UpdateAt = model.GetMillis()
			organisation.Name = oldOrganisation.Name
			organisation.CreatedBy = oldOrganisation.CreatedBy

			if count, err := s.GetMaster().Update(organisation); err != nil {
				result.Err = model.NewAppError("SqlOrganisationStore.Update", "We encountered an error updating the organisation", "id="+ organisation.Id+", "+err.Error())
			} else if count != 1 {
				result.Err = model.NewAppError("SqlOrganisationStore.Update", "We couldn't update the organisation", "id="+ organisation.Id)
			} else {
				result.Data = organisation
			}
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlOrganisationStore) GetByName(name string) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		organisation := model.Organisation{}

		if err := s.GetReplica().SelectOne(&organisation, "SELECT * FROM Organisation WHERE Name = :Name", map[string]interface{}{"Name": name}); err != nil {
			result.Err = model.NewAppError("SqlOrganisationStore.GetByName", "We couldn't find the existing organisation", "name="+name+", "+err.Error())
		}

		result.Data = &organisation

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlOrganisationStore) GetCreatedBy(name string) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}
		var organisations model.Organisations

		if _, err := s.GetReplica().Select(&organisations, "SELECT * FROM Organisation WHERE CreatedBy = :CreatedBy", map[string]interface{}{"CreatedBy": name}); err != nil {
			result.Err = model.NewAppError("SqlOrganisationStore.GetByName", "We couldn't find any existing organisation created By User", "name="+name+", "+err.Error())
		}

		result.Data = &organisations
		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}