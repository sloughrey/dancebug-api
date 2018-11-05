document.addEventListener("DOMContentLoaded", function(event) {
    bindUserEditButtons();
    initializeCreateForm();
});

function initializeCreateForm() {
    var createBtn = document.getElementById('create-user-btn');
    createBtn.addEventListener('click', function(){
        var studioName = document.getElementById('studio-name').value;
        var studioId = document.getElementById('studio-id').value;
        var firstName = document.getElementById('first-name').value;
        var lastName = document.getElementById('last-name').value;
        var gender = document.getElementById('gender-val').value;
        var dob = document.getElementById('dob').value;

        var req = new XMLHttpRequest();

        var editId = document.getElementById('user-edit-id').value;
        params = 'studioName=' + studioName + '&studioID=' + studioId + '&firstName=' + firstName + '&lastName=' + lastName + '&dob=' + dob + '&gender=' + gender;
        if (editId) {
            var reqMethod = 'PUT';
            var url = '/users/' + editId;
            params += '&dancerID=' + editId;
        } else {
            var reqMethod = 'POST';
            var url = '/users';
        }

        req.open(reqMethod, url);
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(params);
        req.onreadystatechange = function() {
            var data = JSON.parse(req.responseText);
            if(req.readyState == 4 && req.status == 200) {
                if(data.status == 'success') {
                    alert(data.statusMsg);
                } else {
                    alert('error creating user');
                }
            } else if (req.readyState == 4 && req.status  == 422) {
                alert('Resource could not be saved due to malformed data');
                return;
            } else {
                console.log('failed request');
                return;
            }
            console.log(data);
        }
    });

    var clearBtn = document.getElementById('clear-user-btn');
    clearBtn.addEventListener("click", function(){
        clearForm();
        $('#user-form-title').text('Create New User');
    });

    bindGenderDropdown();
}

function bindGenderDropdown() {
    var menuItems = document.getElementsByClassName("dropdown-item");
    for (var i = 0; i < menuItems.length; i++) {
        var item = menuItems[i];
        item.addEventListener('click', function(event){
            document.getElementById('gender-val').value = this.text;
            document.getElementById('gender-dd-btn').textContent = this.text;
        });
    }

    $('.dropdown-menu a').click(function(){
        $('#selected').text($(this).text());
      });
}
function bindUserEditButtons() {
    var btns = document.getElementsByClassName("entity-edit-btn");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener('click', function(event){
            var req = new XMLHttpRequest();
            var url = '/users/' + this.dataset.id;

            req.open('GET', url);
            req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            req.send();
            req.onreadystatechange = function() {
                if(req.readyState == 4 && req.status == 200) {
                    var data = JSON.parse(req.responseText);
                    if(data.status == 'success') {
                        var user = data.data;
                        document.getElementById('studio-name').value = user.studioName;
                        document.getElementById('studio-id').value = user.studioID;
                        document.getElementById('first-name').value = user.firstName;
                        document.getElementById('last-name').value = user.lastName;
                        if(user.gender.toLowerCase() == 'male') {
                            document.getElementById('gender-male').click();
                        } else {
                            document.getElementById('gender-female').click();
                        }
                        document.getElementById('dob').value = user.dob;
                        document.getElementById('user-edit-id').value = user.dancerID;
                        document.getElementById('user-form-title').textContent = "Update User";
                        alert('user loaded');
                    } else {
                        alert('could not load user');
                    }
                } else {
                    console.log('failed request');
                }
                
                console.log(data);
            }
        });
    }
}

function clearForm() {
        document.getElementById('studio-name').value = "";
        document.getElementById('studio-id').value = "";
        document.getElementById('first-name').value = "";
        document.getElementById('last-name').value = "";
        document.getElementById('gender-val').value = "";
        document.getElementById('dob').value = "";
        document.getElementById('user-edit-id').value = "";
}