<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $footerText = $_POST['footerText'] ?? '';

    $data = [
        'text' => $footerText
    ];

    file_put_contents("../footer.json", json_encode($data, JSON_PRETTY_PRINT));
    echo "Tallennus onnistui!";
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Admin Pääsivu</title>
        <link rel="stylesheet" href="index.css">

<script>
// Hae nykyinen alatunnisteteksti ja näytä se lomakkeessa
fetch('../load_footer.php')
    .then(response => response.json())
    .then(data => {
        document.getElementById('footerText').value = data.text;
    });

    let editHeadingMode = false;
    let sectionCounter = 2; // Start counter at 2 since we already have sections 1 and 2
    
    // Store sections data
    let sectionsData = [];
        
    function handleHeadingEdit() {
        editHeadingMode = !editHeadingMode;

        const headingElement = document.getElementById("title");
        const inputElement = document.getElementById("titleInput");

        headingElement.hidden = editHeadingMode;
        inputElement.hidden = !editHeadingMode;

        if (!editHeadingMode) {
           const title = inputElement.value;
           headingElement.innerHTML = title;
           localStorage.setItem("title", title);
        }

        document.getElementById("titleButton").innerHTML = editHeadingMode ? "Save" : "Edit";
    }    
    
    const savedLinks = localStorage.getItem("links-list");
    let linksList = savedLinks ? JSON.parse(savedLinks) : [];

    function handleLoad() {
        // Load footer title
        const title = localStorage.getItem("title");
        document.getElementById("title").innerHTML = title || "Default Footer Title";
        document.getElementById("titleInput").value = title || "Default Footer Title";

        // Load logo
        const savedLogo = localStorage.getItem("logo-url");
        if (savedLogo) {
            document.getElementById("kuva1").src = savedLogo;
            document.getElementById("logoInput").value = savedLogo;
        }

        // Load section 1 heading and image
        const section1Heading = localStorage.getItem("section1-heading");
        document.getElementById("section1-heading").innerHTML = section1Heading || "Admin Otsikko 1";
        document.getElementById("section1-heading-input").value = section1Heading || "Admin Otsikko 1";

        const section1Image = localStorage.getItem("section1-image");
        if (section1Image) {
            document.getElementById("kuva2").src = section1Image;
            document.getElementById("section1-image-input").value = section1Image;
        }

        // Load section 2 heading and image
        const section2Heading = localStorage.getItem("section2-heading");
        document.getElementById("section2-heading").innerHTML = section2Heading || "Admin Otsikko 2";
        document.getElementById("section2-heading-input").value = section2Heading || "Admin Otsikko 2";

        const section2Image = localStorage.getItem("section2-image");
        if (section2Image) {
            document.getElementById("kuva3").src = section2Image;
            document.getElementById("section2-image-input").value = section2Image;
        }

        // Load dynamic sections
        const savedSections = localStorage.getItem("dynamic-sections");
        if (savedSections) {
            sectionsData = JSON.parse(savedSections);
            
            // Calculate the highest section number to set sectionCounter
            let highestSectionId = 2; // Start at 2 because we already have sections 1 and 2
            
            sectionsData.forEach(section => {
                highestSectionId = Math.max(highestSectionId, section.id);
                renderDynamicSection(section);
            });
            
            // Set the counter for the next section
            sectionCounter = highestSectionId + 1;
        }

        renderLinks();
    }

    addEventListener("load", handleLoad);
    
    // Section 1 heading editing
    let editSection1HeadingMode = false;
    
    function handleSection1HeadingEdit() {
        editSection1HeadingMode = !editSection1HeadingMode;
        
        const headingElement = document.getElementById("section1-heading");
        const inputElement = document.getElementById("section1-heading-input");
        
        headingElement.hidden = editSection1HeadingMode;
        inputElement.hidden = !editSection1HeadingMode;
        
        if (!editSection1HeadingMode) {
            const heading = inputElement.value;
            headingElement.innerHTML = heading;
            localStorage.setItem("section1-heading", heading);
        }
        
        document.getElementById("section1-heading-button").innerHTML = editSection1HeadingMode ? "Save" : "Edit";
    }
    
    // Section 1 image editing
    let editSection1ImageMode = false;
    
    function handleSection1ImageEdit() {
        editSection1ImageMode = !editSection1ImageMode;
        
        const imgElement = document.getElementById("kuva2");
        const inputElement = document.getElementById("section1-image-input");
        const button = document.getElementById("section1-image-button");
        
        inputElement.hidden = !editSection1ImageMode;
        
        if (!editSection1ImageMode) {
            const newUrl = inputElement.value;
            imgElement.src = newUrl;
            localStorage.setItem("section1-image", newUrl);
        }
        
        button.innerHTML = editSection1ImageMode ? "Save" : "Edit";
    }
    
    // Delete section 1 image
    function handleSection1ImageDelete() {
        if (confirm("Are you sure you want to remove this image?")) {
            const imgElement = document.getElementById("kuva2");
            const inputElement = document.getElementById("section1-image-input");
            
            // Replace with placeholder or empty image
            imgElement.src = "";
            inputElement.value = "";
            localStorage.removeItem("section1-image");
            
            // Show "no image" placeholder
            imgElement.classList.add("hidden-image");
            imgElement.innerHTML = "No Image";
        }
    }
    
    // Add section 1 image
    function handleSection1ImageAdd() {
        const imgElement = document.getElementById("kuva2");
        imgElement.classList.remove("hidden-image");
        imgElement.innerHTML = "";
        
        // Show image input
        const inputElement = document.getElementById("section1-image-input");
        inputElement.hidden = false;
        document.getElementById("section1-image-button").innerHTML = "Save";
        
        editSection1ImageMode = true;
    }
    
    // Section 2 heading editing
    let editSection2HeadingMode = false;
    
    function handleSection2HeadingEdit() {
        editSection2HeadingMode = !editSection2HeadingMode;
        
        const headingElement = document.getElementById("section2-heading");
        const inputElement = document.getElementById("section2-heading-input");
        
        headingElement.hidden = editSection2HeadingMode;
        inputElement.hidden = !editSection2HeadingMode;
        
        if (!editSection2HeadingMode) {
            const heading = inputElement.value;
            headingElement.innerHTML = heading;
            localStorage.setItem("section2-heading", heading);
        }
        
        document.getElementById("section2-heading-button").innerHTML = editSection2HeadingMode ? "Save" : "Edit";
    }
    
    // Section 2 image editing
    let editSection2ImageMode = false;
    
    function handleSection2ImageEdit() {
        editSection2ImageMode = !editSection2ImageMode;
        
        const imgElement = document.getElementById("kuva3");
        const inputElement = document.getElementById("section2-image-input");
        const button = document.getElementById("section2-image-button");
        
        inputElement.hidden = !editSection2ImageMode;
        
        if (!editSection2ImageMode) {
            const newUrl = inputElement.value;
            imgElement.src = newUrl;
            localStorage.setItem("section2-image", newUrl);
        }
        
        button.innerHTML = editSection2ImageMode ? "Save" : "Edit";
    }
    
    // Delete section 2 image
    function handleSection2ImageDelete() {
        if (confirm("Are you sure you want to remove this image?")) {
            const imgElement = document.getElementById("kuva3");
            const inputElement = document.getElementById("section2-image-input");
            
            // Replace with placeholder or empty image
            imgElement.src = "";
            inputElement.value = "";
            localStorage.removeItem("section2-image");
            
            // Show "no image" placeholder
            imgElement.classList.add("hidden-image");
            imgElement.innerHTML = "No Image";
        }
    }
    
    // Add section 2 image
    function handleSection2ImageAdd() {
        const imgElement = document.getElementById("kuva3");
        imgElement.classList.remove("hidden-image");
        imgElement.innerHTML = "";
        
        // Show image input
        const inputElement = document.getElementById("section2-image-input");
        inputElement.hidden = false;
        document.getElementById("section2-image-button").innerHTML = "Save";
        
        editSection2ImageMode = true;
    }

    // Dynamic section functionality
    function addNewSection() {
        const sectionId = sectionCounter++;
        const sectionData = {
            id: sectionId,
            heading: `New Section ${sectionId}`,
            imageUrl: ""
        };
        
        sectionsData.push(sectionData);
        
        // Save updated sections list to localStorage
        localStorage.setItem("dynamic-sections", JSON.stringify(sectionsData));
        
        // Render the new section
        renderDynamicSection(sectionData);
    }
    
    function renderDynamicSection(sectionData) {
        const sectionId = sectionData.id;
        const sectionsContainer = document.getElementById("dynamic-sections");
        
        // Create section container
        const sectionDiv = document.createElement("div");
        sectionDiv.className = "textimage1";
        sectionDiv.id = `section-${sectionId}`;
        
        // Create text container
        const textDiv = document.createElement("div");
        textDiv.className = "teksti";
        
        // Create heading with edit capability
        const heading = document.createElement("h1");
        heading.id = `section${sectionId}-heading`;
        heading.innerHTML = sectionData.heading;
        
        const headingInput = document.createElement("input");
        headingInput.id = `section${sectionId}-heading-input`;
        headingInput.type = "text";
        headingInput.value = sectionData.heading;
        headingInput.placeholder = "Section Heading";
        headingInput.hidden = true;
        
        const headingButton = document.createElement("button");
        headingButton.id = `section${sectionId}-heading-button`;
        headingButton.innerHTML = "Edit";
        headingButton.onclick = function() {
            handleDynamicSectionHeadingEdit(sectionId);
        };
        
        // Add paragraph text
        const paragraph = document.createElement("p");
        paragraph.innerHTML = "Lorem ipsum dolor sit amet. Aut blanditiis eaque eum dignissimos dicta aut architecto expedita ut praesentium assumenda est sint adipisci. Aut aperiam iste ut sunt libero ea culpa veritatis rem architecto dolores quo explicabo eligendi est deleniti consectetur et aliquam consectetur.";
        
        // Add section controls (delete section)
        const sectionControls = document.createElement("div");
        sectionControls.className = "section-controls";
        
        const deleteSection = document.createElement("button");
        deleteSection.innerHTML = "Delete Section";
        deleteSection.onclick = function() {
            handleDeleteSection(sectionId);
        };
        
        sectionControls.appendChild(deleteSection);
        
        // Add all text elements to text container
        textDiv.appendChild(heading);
        textDiv.appendChild(headingInput);
        textDiv.appendChild(headingButton);
        textDiv.appendChild(paragraph);
        textDiv.appendChild(sectionControls);
        
        // Create image container
        const imageContainer = document.createElement("div");
        imageContainer.className = "image-container";
        
        // Create image element
        const img = document.createElement("img");
        img.id = `kuva-section${sectionId}`;
        img.width = 450;
        img.height = 300;
        
        if (sectionData.imageUrl) {
            img.src = sectionData.imageUrl;
        } else {
            img.classList.add("hidden-image");
            img.innerHTML = "No Image";
        }
        
        // Create image input field
        const imageInput = document.createElement("input");
        imageInput.id = `section${sectionId}-image-input`;
        imageInput.type = "text";
        imageInput.placeholder = "Image URL";
        imageInput.value = sectionData.imageUrl || "";
        imageInput.hidden = true;
        
        // Create image controls
        const imageControls = document.createElement("div");
        imageControls.className = "image-controls";
        
        const editButton = document.createElement("button");
        editButton.id = `section${sectionId}-image-button`;
        editButton.innerHTML = "Edit";
        editButton.onclick = function() {
            handleDynamicSectionImageEdit(sectionId);
        };
        
        const deleteButton = document.createElement("button");
        deleteButton.innerHTML = "Delete";
        deleteButton.onclick = function() {
            handleDynamicSectionImageDelete(sectionId);
        };
        
        const addButton = document.createElement("button");
        addButton.innerHTML = "+";
        addButton.onclick = function() {
            handleDynamicSectionImageAdd(sectionId);
        };
        
        // Add buttons to controls
        imageControls.appendChild(editButton);
        imageControls.appendChild(deleteButton);
        imageControls.appendChild(addButton);
        
        // Add all image elements to image container
        imageContainer.appendChild(img);
        imageContainer.appendChild(imageInput);
        imageContainer.appendChild(imageControls);
        
        // Add text and image to section
        sectionDiv.appendChild(textDiv);
        sectionDiv.appendChild(imageContainer);
        
        // Add section to page
        sectionsContainer.appendChild(sectionDiv);
    }
    
    // Dynamic section heading edit functionality
    function handleDynamicSectionHeadingEdit(sectionId) {
        const headingElement = document.getElementById(`section${sectionId}-heading`);
        const inputElement = document.getElementById(`section${sectionId}-heading-input`);
        const buttonElement = document.getElementById(`section${sectionId}-heading-button`);
        
        const isEditing = headingElement.hidden;
        
        // Toggle visibility
        headingElement.hidden = !isEditing;
        inputElement.hidden = isEditing;
        
        if (isEditing) {
            // Save changes
            const heading = inputElement.value;
            headingElement.innerHTML = heading;
            
            // Update data in our array
            const sectionIndex = sectionsData.findIndex(s => s.id === sectionId);
            if (sectionIndex !== -1) {
                sectionsData[sectionIndex].heading = heading;
                localStorage.setItem("dynamic-sections", JSON.stringify(sectionsData));
            }
            
            buttonElement.innerHTML = "Edit";
        } else {
            buttonElement.innerHTML = "Save";
        }
    }
    
    // Dynamic section image edit functionality
    function handleDynamicSectionImageEdit(sectionId) {
        const imgElement = document.getElementById(`kuva-section${sectionId}`);
        const inputElement = document.getElementById(`section${sectionId}-image-input`);
        const buttonElement = document.getElementById(`section${sectionId}-image-button`);
        
        const isEditing = !inputElement.hidden;
        
        // Toggle input visibility
        inputElement.hidden = isEditing;
        
        if (isEditing) {
            // Save changes
            const newUrl = inputElement.value;
            
            if (newUrl) {
                imgElement.src = newUrl;
                imgElement.classList.remove("hidden-image");
                imgElement.innerHTML = "";
                
                // Update data in our array
                const sectionIndex = sectionsData.findIndex(s => s.id === sectionId);
                if (sectionIndex !== -1) {
                    sectionsData[sectionIndex].imageUrl = newUrl;
                    localStorage.setItem("dynamic-sections", JSON.stringify(sectionsData));
                }
            }
            
            buttonElement.innerHTML = "Edit";
        } else {
            buttonElement.innerHTML = "Save";
        }
    }
    
    // Dynamic section image delete functionality
    function handleDynamicSectionImageDelete(sectionId) {
        if (confirm("Are you sure you want to remove this image?")) {
            const imgElement = document.getElementById(`kuva-section${sectionId}`);
            const inputElement = document.getElementById(`section${sectionId}-image-input`);
            
            // Clear image
            imgElement.src = "";
            inputElement.value = "";
            
            // Show placeholder
            imgElement.classList.add("hidden-image");
            imgElement.innerHTML = "No Image";
            
            // Update data in our array
            const sectionIndex = sectionsData.findIndex(s => s.id === sectionId);
            if (sectionIndex !== -1) {
                sectionsData[sectionIndex].imageUrl = "";
                localStorage.setItem("dynamic-sections", JSON.stringify(sectionsData));
            }
        }
    }
    
    // Dynamic section image add functionality
    function handleDynamicSectionImageAdd(sectionId) {
        const imgElement = document.getElementById(`kuva-section${sectionId}`);
        const inputElement = document.getElementById(`section${sectionId}-image-input`);
        const buttonElement = document.getElementById(`section${sectionId}-image-button`);
        
        // Show input field
        inputElement.hidden = false;
        buttonElement.innerHTML = "Save";
        
        // Clear placeholder if exists
        imgElement.classList.remove("hidden-image");
        imgElement.innerHTML = "";
    }
    
    // Delete an entire dynamic section
    function handleDeleteSection(sectionId) {
        if (confirm("Are you sure you want to delete this entire section?")) {
            // Remove from DOM
            const sectionElement = document.getElementById(`section-${sectionId}`);
            sectionElement.remove();
            
            // Remove from data array
            const sectionIndex = sectionsData.findIndex(s => s.id === sectionId);
            if (sectionIndex !== -1) {
                sectionsData.splice(sectionIndex, 1);
                localStorage.setItem("dynamic-sections", JSON.stringify(sectionsData));
            }
        }
    }

    function handleAddLink(e) {
        const button = e.target;
        const isAdding = button.innerHTML === "+";

        const refInput = document.getElementById("linkRef");
        const nameInput = document.getElementById("linkName");

        if (isAdding) {
            refInput.hidden = false;
            nameInput.hidden = false;
            button.innerHTML = "Save";
        } else {
            const newLink = {
                href: refInput.value,
                name: nameInput.value
            };

            if (newLink.name && newLink.href) {
                linksList.push(newLink);
                localStorage.setItem("links-list", JSON.stringify(linksList));
                refInput.value = "";
                nameInput.value = "";
                refInput.hidden = true;
                nameInput.hidden = true;
                button.innerHTML = "+";
                renderLinks();
            }
        }
    }

    function handleEditLink(index) {
        const link = linksList[index];
        const newHref = prompt("Enter new URL:", link.href);
        const newName = prompt("Enter new name:", link.name);

        if (newHref && newName) {
            linksList[index] = { href: newHref, name: newName };
            localStorage.setItem("links-list", JSON.stringify(linksList));
            renderLinks();
        }
    }

    function handleDeleteLink(index) {
        if (confirm("Are you sure you want to delete this link?")) {
            linksList.splice(index, 1);
            localStorage.setItem("links-list", JSON.stringify(linksList));
            renderLinks();
        }
    }

    function renderLinks() {
        const listElement = document.getElementById("link-list");
        listElement.innerHTML = ""; // Clear current links

        linksList.forEach((link, index) => {
            const container = document.createElement("div");
            container.className = "link-item";

            // Create name input
            const nameInput = document.createElement("input");
            nameInput.type = "text";
            nameInput.value = link.name;
            nameInput.disabled = true;

            // Create href input
            const hrefInput = document.createElement("input");
            hrefInput.type = "text";
            hrefInput.value = link.href;
            hrefInput.disabled = true;

            // Edit button
            const editBtn = document.createElement("button");
            editBtn.innerHTML = "Edit";
            editBtn.onclick = () => {
                const editing = editBtn.innerHTML === "Save";
                if (editing) {
                    // Save
                    linksList[index].name = nameInput.value;
                    linksList[index].href = hrefInput.value;
                    localStorage.setItem("links-list", JSON.stringify(linksList));
                    nameInput.disabled = true;
                    hrefInput.disabled = true;
                    editBtn.innerHTML = "Edit";
                } else {
                    // Enable editing
                    nameInput.disabled = false;
                    hrefInput.disabled = false;
                    editBtn.innerHTML = "Save";
                }
            };

            // Delete button
            const deleteBtn = document.createElement("button");
            deleteBtn.innerHTML = "Delete";
            deleteBtn.onclick = () => {
                linksList.splice(index, 1);
                localStorage.setItem("links-list", JSON.stringify(linksList));
                renderLinks();
            };

            container.appendChild(nameInput);
            container.appendChild(hrefInput);
            container.appendChild(editBtn);
            container.appendChild(deleteBtn);

            listElement.appendChild(container);
        });
    }

    let editLogoMode = false;

    function handleLogoEdit() {
        editLogoMode = !editLogoMode;
        
        const imgElement = document.getElementById("kuva1");
        const inputElement = document.getElementById("logoInput");
        const button = document.getElementById("logoBtn");

        imgElement.hidden = editLogoMode;
        inputElement.hidden = !editLogoMode;

        if (!editLogoMode) {
            const newUrl = inputElement.value;
            imgElement.src = newUrl;
            localStorage.setItem("logo-url", newUrl);
        }

        button.innerHTML = editLogoMode ? "Save" : "Edit";
    }
</script>

    </head>
    <body>
        <div class="ylapalkki">
            <img src="https://download.logo.wine/logo/Porsche/Porsche-Logo.wine.png" width="210px" height="140px" id="kuva1">
            <input id="logoInput" type="text" placeholder="Image URL" hidden>
            <button id="logoBtn" onclick="handleLogoEdit()">Edit</button>

        </div>
        <div class="section">
            <div class="textimage1">
                <div class="teksti">
                    <h1 id="section1-heading">Admin Otsikko 1</h1>
                    <input id="section1-heading-input" type="text" placeholder="Section 1 Heading" hidden>
                    <button id="section1-heading-button" onclick="handleSection1HeadingEdit()">Edit</button>
                    <p>Lorem ipsum dolor sit amet. Aut blanditiis eaque eum dignissimos dicta aut architecto expedita ut praesentium assumenda est sint adipisci.
                    Aut aperiam iste ut sunt libero ea culpa veritatis rem architecto dolores quo explicabo eligendi est deleniti consectetur et aliquam consectetur.
                    33 eius nulla sit incidunt praesentium non omnis voluptatem ut maiores alias. Et optio accusantium a saepe suscipit sit excepturi porro a error quam et libero accusantium.
                    Lorem ipsum dolor sit amet. Aut blanditiis eaque eum dignissimos dicta aut architecto expedita ut praesentium assumenda est sint adipisci.
                    Aut aperiam iste ut sunt libero ea culpa veritatis rem architecto dolores quo explicabo eligendi est deleniti consectetur et aliquam consectetur.
                    33 eius nulla sit incidunt praesentium non omnis voluptatem ut maiores alias. Et optio accusantium a saepe suscipit sit excepturi porro a error quam et libero accusantium.</p>
                </div>
                <div class="image-container">
                    <img src="https://content-hub.imgix.net/7pAtSAhW9FPTslxOpVVd93/332be8b3784aa2516a5d43e3b2da28a3/six-20things-20you-20need-20to-20know-20about-20the-20porsche-20997-206.jpg?w=1308" id="kuva2" width="450px" height="300px">
                    <input id="section1-image-input" type="text" placeholder="Image URL" hidden>
                    <div class="image-controls">
                        <button id="section1-image-button" onclick="handleSection1ImageEdit()">Edit</button>
                        <button onclick="handleSection1ImageDelete()">Delete</button>
                        <button onclick="handleSection1ImageAdd()">+</button>
                    </div>
                </div>
            </div>
            <div class="textimage1">
                <div class="teksti">
                    <h1 id="section2-heading">Admin Otsikko 2</h1>
                    <input id="section2-heading-input" type="text" placeholder="Section 2 Heading" hidden>
                    <button id="section2-heading-button" onclick="handleSection2HeadingEdit()">Edit</button>
                    <p>Lorem ipsum dolor sit amet. Aut blanditiis eaque eum dignissimos dicta aut architecto expedita ut praesentium assumenda est sint adipisci.
                    Aut aperiam iste ut sunt libero ea culpa veritatis rem architecto dolores quo explicabo eligendi est deleniti consectetur et aliquam consectetur.
                    33 eius nulla sit incidunt praesentium non omnis voluptatem ut maiores alias. Et optio accusantium a saepe suscipit sit excepturi porro a error quam et libero accusantium.
                    Lorem ipsum dolor sit amet. Aut blanditiis eaque eum dignissimos dicta aut architecto expedita ut praesentium assumenda est sint adipisci.
                    Aut aperiam iste ut sunt libero ea culpa veritatis rem architecto dolores quo explicabo eligendi est deleniti consectetur et aliquam consectetur.
                    33 eius nulla sit incidunt praesentium non omnis voluptatem ut maiores alias. Et optio accusantium a saepe suscipit sit excepturi porro a error quam et libero accusantium.</p>
                </div>
                <div class="image-container">
                    <img src="https://images-porsche.imgix.net/-/media/E6F1452793D246FEAE326E1B2779C681_5FEF67AE27D845F487A5AC2202D1137B_020-info-slider-2-1-997-911-gt2-2008-2009?w=1299&q=85&auto=format" id="kuva3" width="450px" height="300px">
                    <input id="section2-image-input" type="text" placeholder="Image URL" hidden>
                    <div class="image-controls">
                        <button id="section2-image-button" onclick="handleSection2ImageEdit()">Edit</button>
                        <button onclick="handleSection2ImageDelete()">Delete</button>
                        <button onclick="handleSection2ImageAdd()">+</button>
                    </div>
                </div>
            </div>
            
            <!-- Container for dynamically added sections -->
            <div id="dynamic-sections"></div>
            
            <!-- Add section button -->
            <button id="add-section-button" onclick="addNewSection()">Add New Section</button>
        </div>
        <div class="footer">
            <div class="column1">
            <form method="post" action="index.php">
            <label for="footerText">Alatunnisteen teksti:</label><br>
            <input type="text" id="footerText" name="footerText"><br><br>
            <input type="submit" value="Tallenna">
            </form>

            <form action="upload_logo.php" method="post" enctype="multipart/form-data">
            <label>Valitse logokuva:</label><br>
            <input type="file" name="logo"><br><br>
            <input type="submit" value="Lataa Logo">
        </form>
                <h1 id="title"></h1>
                <input id="titleInput" hidden>
                <button id="titleButton" onclick="handleHeadingEdit()">Edit</button>
                <p>Lorem ipsum dolor sit amet. Eum doloremque dolor quo totam dicta sed voluptatem rerum
                    ut isteaccusantium autlaudantium adipisci aut debitis harum eos quae quae.
                    Et veritatis provident eum perferendis quis cum dolorum quaerat ut molestias obcaecati sed quia labore!</p>
                <p>@ 2024, Companys name, All rights reserved</p>    
            </div>
            <div id="link-list"></div>
            <div id="new-link-form">
                <input type="text" id="linkName" placeholder="Link text" hidden />
                <input type="text" id="linkRef" placeholder="Link URL" hidden />
                <button onclick="handleAddLink(event)">+</button>
            </div>
        </div>
    </body>
</html>