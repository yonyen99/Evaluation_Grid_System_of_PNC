/**
 * Call to execute validation block of codes for [List] form of roles.
 * @return void
 */
function validListRole(){
    // show confirm delete message
    $('.role-delete-btn').on('click', function(){
        return confirm('Do you really want to delete this role record?');
    });

}

/**
 * Call to execute validation block of codes for [Add] and [Edit]
 * form of roles.
 * @return void
 */
function validAddnEditRole(){
    // clear all inputs    
    $('#role-reset-btn').on('click', function(e){
        e.preventDefault();
        if( confirm('Are you sure to clear all input fields?') ){
            $('#role-form')[0].reset();
            // clear permission selected option
            $('.filter-option-inner-inner').empty();
            $('.filter-option-inner-inner').html('Nothing Selected');
        };
    });

    // validate data on from submit
    $('#role-form').on('submit', function(){
        // check name value
        nameValue = $('#name').val();
        nameResult = checkAlphanumericnWhitspaceOnly(nameValue);
        if(!nameResult.data){
            alert(nameResult.message + ' on role name!');
            return false;
        }

        // check empty role checkbox
        var roleCheckbox = $('input[name="permissionsCheckbox[]"]:checked');
        if(roleCheckbox.length == 0){
            alert('Role should at least has one or more permission, please assign permission for this role by check on the checkbox!');
            return false;
        }

    });

}

/**
 * Auto selected permissions of current role provided from database.
 * @return void
 */
function autoSelectPermissionsOfCurrentRole(){
    // auto selected permissions based on current role
    permissionsIdsArr =  JSON.parse( $('#role-permissions').val() );
    $('select[name="permissions[]"]').val(permissionsIdsArr);
}

/**
 * Auto checked array of permission checkbox based on 
 * current role permissions provided from database.
 * @return void
 */
function autoCheckPermissionsOfCurrentRole(){
    // loop current role permission and search matching all checkbox and set ture if found
    let rolePermissionNamesArr = JSON.parse( $('#rolePermissionNamesArr').val() );
    rolePermissionNamesArr.filter(function(value){
        $('input[name="permissionsCheckbox[]"]').map(function(index, obj){
            if(obj.value == value){
                obj.checked = true;
            }
        });
    });    
}
