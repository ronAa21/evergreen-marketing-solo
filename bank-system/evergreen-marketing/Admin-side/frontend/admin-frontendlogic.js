// displays initial contents
loadContent()

let modalOverlay = document.querySelector(".modal-overlay")

// close modal
closeModal(modalOverlay)

  // add new contents (button)
  let addContent = document.getElementById("add-content")

  addContent.addEventListener("click", function() {

  // opens the modal
  modalOverlay.style.display = "flex"
  });

  // publish content 
  let publishContent = document.getElementById("publish-content");

  publishContent.addEventListener("click", async function() {

    // call the save content function
    saveContent();
  });

  // close modal (pang lahatan)
  function closeModal(modal) {
    let close = document.querySelector(".close-modal")

    close.addEventListener("click", function() {
      modal.style.display = 'none';
    })
  }

  // save the content to database
  function saveContent() {
    let data = {
        action: 'create_post',
        title: document.getElementById("title").value,
        body: document.getElementById("body").value,
        author_id: 1 // Hardcoded, only one admin lang naman e
    };

    // Send it to the backend
    fetch('../backend/actions.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        alert("Content Published!");
        location.reload(); // instantly show the content
    })
    .catch(error => {
      console.error('Error:', error)
      alert('Something went wrong!')
    });
  }

  // displays the contents
  function contentView(item) {

    let contentView = document.querySelector(".content-view"); // parent container for content

    let contentBody = document.createElement("div");

    let titleShow = document.createElement("h2"); // shows content title
    titleShow.textContent = item.title;

    let bodyShow = document.createElement("p"); // shows content body
    bodyShow.textContent = item.body;

    // action buttons
    let editContentBtn = document.createElement("button");
    editContentBtn.textContent = "Edit"

    let deleteContentBtn = document.createElement("button");
    deleteContentBtn.textContent = "Delete"
    deleteContentBtn.setAttribute("data-id", item.id)

    // appending
    contentBody.appendChild(titleShow);
    contentBody.appendChild(bodyShow);
    contentBody.appendChild(editContentBtn);
    contentBody.appendChild(deleteContentBtn);
    contentView.appendChild(contentBody);

    // button functions
    deleteContentBtn.addEventListener("click", () => {
      deleteContent(item)
    }) 

    editContentBtn.addEventListener("click", () => {
      editContent(item)
    })
  }

  // Function to get content from the server
  async function loadContent() {
    try {
      const response = await fetch('../backend/actions.php'); // The file with the GET logic above
      const contents = await response.json();
      
      // Clear the container first so you don't get duplicates
      document.querySelector(".content-view").innerHTML = "";

      // 2. Loop through data and call your function
      contents.forEach(item => {
        contentView(item); // pass the data
      });
    } catch (error) {
        console.error("Error loading content:", error);
        alert('Something went wrong!')
    }
  }

  // function to delete contents from the database
async function deleteContent(item) {

  // 1. Confirm with the user before wiping data
  let confirmation = confirm("Are you sure you want to delete this post?")

  if(confirmation) {
    try {
      const response = await fetch("../backend/actions.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        // 2. Send the action name and the specific ID
        body: JSON.stringify({
          action: "delete_post",
          id: item.id
        }),
      });

      const result = await response.json();

    if (result.success) {
        alert(result.message);
        // 3. Refresh the UI (e.g., reload the list or remove the element)
        location.reload(); 
      } else {
        alert("Error: " + result.message);
      }
    } catch (error) {
      console.error("Fetch error:", error);
      alert("Something went wrong!");
    }
  }
}

// function for editing content from the database
async function editContent(item) {

  let newTitle = prompt(`Enter new title for ${item.title}`)
  // If user hits 'Cancel', stop the function
  if (newTitle === null) return;

  let newContent = prompt(`Enter new content for ${newTitle}`)
  if (newContent === null) return;

  try {
    const response = await fetch('../backend/actions.php', {
      method: 'POST',
      headers: {
        "Content-Type": 'application/json',
      },
      body: JSON.stringify({
        action: "edit_post",
        id: item.id,
        title: newTitle,
        body: newContent
      })
    })

    const result = await response.json();

    if (result.success) {
      alert("Updated successfully!");
      location.reload(); // Refresh to see changes
    } else {
      console.error("Update failed: " + result.message);
    }

  } catch (error) {
    console.error("Fetch error:", error);
    alert("Something went wrong!");
  }
}
  
  // Prevent form submission and use AJAX instead
  document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault();
    publishContent.click(); // Trigger the AJAX publish
  });