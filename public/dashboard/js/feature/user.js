/**
 * Call to execute validation block of codes for [List] form of users.
 * @return void
 */
function validListUser(){

    // show confirm delete message
    $('.user-delete-btn').on('click', function(){
        return confirm('Do you really want to delete this user record?');
    });

}

/**
 * Call to execute validation block of codes for [Add] and [Edit]
 * form of user.
 * @param [String] option [ add, edit ]
 * @return void
 */
function validAddnEditUser(option){
    // clear all inputs
    clearInputFields('#user-reset-btn', '#user-form');
    if(option == 'edit'){
        // on password reset checkbox checked enable password input
        onResetCheckboxResetPassword('#reset-password-checkbox', '#password');
    }
    
    // validate data on from submit
    $('#user-form').on('submit', function(){
        
        // check valid username
        usernameValue =$('#username').val();
        usernameResult = checkStringnNumberOnly(usernameValue);
        if(!usernameResult.data){
            alert(usernameResult.message + ' on username field without whitespace!');
            return false;
        }

        // check valid firstname
        firstnameValue = $('#firstname').val();
        firstnameResult = checkStringOnly(firstnameValue);
        if(!firstnameResult.data){
            alert(firstnameResult.message + ' on firstname field!');
            return false;
        }

        // check valid lastname
        lastnameValue = $('#lastname').val();
        lastnameResult = checkStringOnly(lastnameValue);
        if(!lastnameResult.data){
            alert(lastnameResult.message + ' on lastname field!');
            return false;
        }

        // check valid email address
        emailValue = $('#email').val();
        emailResult = checkValidEmail(emailValue);
        if(!emailResult.data){
            alert(emailResult.message);
            return false;
        }

        // check empty permission
        permissionsSelectedOption = $('select[name="permissions[]"]').val();
        if(permissionsSelectedOption.length == 0){
            alert('User should at least provide a permission!');
            return false;
        }
    });

}

/**
 * On password reset checkbox checked enable and disable password input.
 * @param [Html_Element_Id_Name] checkboxElementIdName 
 * @param [Html_Element_Id_Name] passwordInputName 
 * @returns void
 */
function onResetCheckboxResetPassword(checkboxElementIdName, passwordInputIdName){
    $(checkboxElementIdName).on('change', function(){
        if( $(this).is(':checked') ) {
            $(passwordInputIdName).removeAttr('disabled');
            $(passwordInputIdName).prop('required', true);
        } else {
            $(passwordInputIdName).attr('disabled', true);        
            $(passwordInputIdName).removeAttr('required');
        }
    });
}


/**
 * Check empty array of checkbox with the same class name.
 * @param  [Html_ClassName_Array] checkboxNameArr
 * @return [Boolean] valide
 */
function checkEmptyPermissionCheckbox(checkboxNameArr){
    valide = false;

    checkboxLength = $('input[name="'+checkboxNameArr+'"]:checked').length;

    checkboxLength > 0 ? valide = true : valide;

    return valide;
}

/**
 * Show or hide passowrd using checkbox.
 * @return void; 
 */
function showPassword() {
    var x = document.getElementById("password");
    if (x.type === "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

/**
 * Set checkbox to checked on matching given group 
 * of checkbox class name and array of ids.
 * @param  [Html_Element_IdName] idNameOfhiddentIdsArr
 * @param  [Html_Element_ClassName] classNameOfGroupCheckboxs
 * @return void;
 */
function matchGroupCheckboxsDoingCheck(idNameOfhiddentIdsArr, classNameOfGroupCheckboxs){
    userPermissionsArr = ($(idNameOfhiddentIdsArr).val()).split(',');
    
    userPermissionsArr.map(function(permissionObj){
        $(classNameOfGroupCheckboxs).each(function(){
            $(this).val() == permissionObj ? $(this).attr('checked', true) : '';
        });
    });
}

/**
 * Check regex of value contained only alpabets and whitespace.
 * @param [String] value
 * @param [String] message
 * @return [ObjectRespond] data | message
 */
function checkStringOnly(value){
    if( !value.match(/^[a-zA-Z]+$/gm) ){
        var respond = { data: false , message : 'Only string are allow and should not contain whitespace'};
        return respond;
    } else {
        var respond = { data: value, message: 'Value valid'};   
        return respond;
    }
}