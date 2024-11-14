let toggleEditList = document.querySelectorAll(".toggleEdit");
let editFormList = document.getElementsByClassName("editForm");
let newNameList = document.getElementsByClassName("newName");

console.log("All Ready");

console.log(toggleEditList);

toggleEditList.forEach(function (button) {
    button.addEventListener('click', function () {
        console.log('Element clicked!', button);

        hideAll();
        toggleEditForm(button);
    });
});

function toggleEditForm(button) {
    let editForm = button.nextElementSibling;
    console.log(editForm);
    if (editForm.classList.contains("hide")) {
        editForm.classList.remove("hide");
        console.log("Removing hide")
    } else {
        editForm.classList.add("hide");
        console.log("Adding hide")
        newName.value = "";
    }
}

function hideAll() {
    toggleEditList.forEach(function (button) {
        let editForm = button.nextElementSibling;

        editForm.classList.add("hide");
    })
}