let editFaceMode = false

function handleFacebookEdit() {

    editFaceMode = !editFaceMode

    console.log("after change", editFaceMode)

    const FaceElement = document.getElementById("Facebook")
    const FaceInput = document.getElementById("FaceInput")

    if (!editFaceMode) {
        const facebook = FaceInput.value
        FaceElement.innerHTML = facebook
        localStorage.setItem("Facebook", facebook)
    }

    FaceElement.hidden = editFaceMode
    FaceInput.hidden = !editFaceMode
    document.getElementById("FaceButton").innerHTML = editFaceMode ? "Save" : "Edit"

}

function handleFacebookLoad() {
    const facebook = localStorage.getItem("Facebook")
    document.getElementById("Facebook").innerHTML = facebook
    document.getElementById("FaceInput").value = facebook
}

addEventListener("facebookload", handleFacebookLoad)


