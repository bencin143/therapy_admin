package store

import (
	"github.com/mattermost/platform/model"
// "github.com/mattermost/platform/utils"
)

type SqlOrganisationUnitStore struct {
	*SqlStore
}

func NewSqlOrganisationUnitStore(sqlStore *SqlStore) OrganisationUnitStore {
	s := &SqlOrganisationUnitStore{sqlStore}

	for _, db := range sqlStore.GetAllConns() {
		table := db.AddTableWithName(model.OrganisationUnit{}, "OrganisationUnit").SetKeys(false, "Id")
		table.ColMap("Id").SetMaxSize(26)
		table.ColMap("Organisation").SetMaxSize(64)
		table.ColMap("OrganisationUnit").SetMaxSize(64)
		table.ColMap("CreatedBy").SetMaxSize(128)
		table.SetUniqueTogether("Organisation", "OrganisationUnit")
	}
	return s
}

func (s SqlOrganisationUnitStore) UpgradeSchemaIfNeeded() {
}

func (s SqlOrganisationUnitStore) Save(organisationUnit *model.OrganisationUnit) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}
		if len(organisationUnit.Id) > 0 {
			result.Err = model.NewAppError("SqlOrganisationStore.Save",
				"Must call update for exisiting organisation Unit", "id="+ organisationUnit.Id)
			storeChannel <- result
			close(storeChannel)
			return
		}

		organisationUnit.PreSave()


		if err := s.GetMaster().Insert(organisationUnit); err != nil {
			if IsUniqueConstraintError(err.Error(), "Name", "organisation_unit_name_key") {
				result.Err = model.NewAppError("SqlOrganisationUnitStore.Save", "A Organisation Unit with that domain already exists", "id="+ organisationUnit.Id+", "+err.Error())
			} else {
				result.Err = model.NewAppError("SqlOrganisationUnitStore.Save", "We couldn't save the organisation Unit", "id="+ organisationUnit.Id+", "+err.Error())
			}
		} else {
			result.Data = organisationUnit
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlOrganisationUnitStore) Update(organisationUnit *model.OrganisationUnit) StoreChannel {

	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		organisationUnit.PreUpdate()

		if oldResult, err := s.GetMaster().Get(model.OrganisationUnit{}, organisationUnit.Id); err != nil {
			result.Err = model.NewAppError("SqlOrganisationUnitStore.Update", "We encountered an error finding the organisation Unit", "id="+ organisationUnit.Id+", "+err.Error())
		} else if oldResult == nil {
			result.Err = model.NewAppError("SqlOrganisationUnitStore.Update", "We couldn't find the existing organisation to update", "id="+ organisationUnit.Id)
		} else {
			oldOrganisationUnit := oldResult.(*model.OrganisationUnit)
			organisationUnit.CreateAt = oldOrganisationUnit.CreateAt
			organisationUnit.UpdateAt = model.GetMillis()
			organisationUnit.OrganisationUnit = oldOrganisationUnit.OrganisationUnit
			organisationUnit.Organisation = oldOrganisationUnit.Organisation
			organisationUnit.CreatedBy = oldOrganisationUnit.CreatedBy

			if count, err := s.GetMaster().Update(organisationUnit); err != nil {
				result.Err = model.NewAppError("SqlOrganisationUnitStore.Update", "We encountered an error updating the organisation Unit", "id="+ organisationUnit.Id+", "+err.Error())
			} else if count != 1 {
				result.Err = model.NewAppError("SqlOrganisationUnitStore.Update", "We couldn't update the organisation Unit", "id="+ organisationUnit.Id)
			} else {
				result.Data = organisationUnit
			}
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

func (s SqlOrganisationUnitStore) GetByName(name string) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		organisationUnit := model.OrganisationUnit{}

		if err := s.GetReplica().SelectOne(&organisationUnit, "SELECT * FROM OrganisationUnit WHERE OrganisationUnit = :Name", map[string]interface{}{"Name": name}); err != nil {
			result.Err = model.NewAppError("SqlOrganisationUnitStore.GetByName", "We couldn't find the existing organisation Unit", "name="+name+", "+err.Error())
		}

		result.Data = &organisationUnit

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}