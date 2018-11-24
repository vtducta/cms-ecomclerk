<div class="form-group">

    <label class="col-sm-3">Permissions:</label>

    <div class="col-sm-7">
        <table class="table table-bordered">
            <tr>
                <th colspan="2"><input type="checkbox" class="checkbox-boss">
                    Select/Deselect
                </th>
                <th>List/View</th>
                <th>Add</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>

            <?php
            $i = 1;
            $permissions = json_decode($userType->default_permissions);
            foreach (\App\Modules\Modules\Module::all() as $module):
                $slug = $module->slug;
                $basicPermissions = explode(',', $module->permissions);
                ?>
                <tr>
                    <td colspan="2"><strong>{{ $module->name }}</strong></td>
                    <?php foreach ($basicPermissions as $p): ?>
                        <td align="left">
                            <input type="checkbox" id="permission_{{ $p }}"
                                   name="permissions[{{ $slug }}][{{ $p }}]" value="1"
                                <?php echo isChecked(1, @$permissions->$slug->$p); ?>
                                   class="checkbox-slaves">
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>