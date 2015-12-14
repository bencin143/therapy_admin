package store

import (
	"github.com/mattermost/platform/model"
// "github.com/mattermost/platform/utils"
)

type SqlRoleStore struct {
	*SqlStore
}

func NewSqlRoleStore(sqlStore *SqlStore) RoleStore {
	s := &SqlRoleStore{sqlStore}

	for _, db := range sqlStore.GetAllConns() {
		table := db.AddTableWithName(model.Role{}, "Role").SetKeys(false, "Id")
		table.ColMap("Id").SetMaxSize(26)
		table.ColMap("RoleName").SetMaxSize(64)
		table.ColMap("UniversalRole").SetMaxSize(64)
		table.ColMap("OrganisationUnit").SetMaxSize(64)
		table.SetUniqueTogether("RoleName", "OrganisationUnit")
	}
	return s
}

func (s SqlRoleStore) UpgradeSchemaIfNeeded() {
}

func (s SqlRoleStore) Save(role *model.Role) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}
		if len(role.Id) > 0 {
			result.Err = model.NewAppError("SqlRoleStore.Save",
				"Must call update for exisiting organisation", "id="+ role.Id)
			storeChannel <- result
			close(storeChannel)
			return
		}

		role.PreSave()


		if err := s.GetMaster().Insert(role); err != nil {
			if IsUniqueConstraintError(err.Error(), "Name", "role_name") {
				result.Err = model.NewAppError("SqlRoleStore.Save", "A Role with that domain already exists", "id="+ role.Id+", "+err.Error())
			} else {
				result.Err = model.NewAppError("SqlRoleStore.Save", "We couldn't save the role", "id="+ role.Id+", "+err.Error())
			}
		} else {
			result.Data = role
		}

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}

/*func (s SqlRoleStore) Update(role *model.Role) StoreChannel {

	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		role.PreUpdate()

		if oldResult, err := s.GetMaster().Get(model.Role{}, role.Id); err != nil {
			result.Err = model.NewAppError("SqlRoleStore.Update", "We encountered an error finding the organisation Unit", "id="+ organisationUnit.Id+", "+err.Error())
		} else if oldResult == nil {
			result.Err = model.NewAppError("SqlRoleStore.Update", "We couldn't find the existing organisation to update", "id="+ organisationUnit.Id)
		} else {
			oldOrganisationUnit := oldResult.(*model.Role)
			organisationUnit.CreateAt = oldOrganisationUnit.CreateAt
			organisationUnit.UpdateAt = model.GetMillis()
			organisationUnit.UnitName = oldOrganisationUnit.UnitName

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
}*/

func (s SqlRoleStore) GetRoles(name string) StoreChannel {
	storeChannel := make(StoreChannel)

	go func() {
		result := StoreResult{}

		role := model.Role{}

		if err := s.GetReplica().SelectOne(&role, "SELECT * FROM Role WHERE OrganisationUnit = :Name limit 1", map[string]interface{}{"Name": name}); err != nil {
			result.Err = model.NewAppError("SqlOrganisationUnitStore.GetByName", "We couldn't find the existing organisation Unit", "name="+name+", "+err.Error())
		}

		result.Data = &role

		storeChannel <- result
		close(storeChannel)
	}()

	return storeChannel
}