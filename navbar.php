
<div class="navbar_all">
    <a href="index.php">
        <div class="logo">

        </div>
    </a>
    <div class="nav_links">
     <a href="index.php">Search Members</a>
     <a href="add_member.php">Add Member</a>
     <a href="add_member.php">Add Member</a>
    </div>


    <button id="toggleButton">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>
    <div class="mobile">
    <a href="index.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="executive.php">Our Exectives</a>
       <a href="photo.php">Photo Gallery</a>
       <a href="contact.php">Contact Us</a>
    </div>


</div>


<script>
    // Get the button and sidebar elements
    var toggleButton = document.getElementById("toggleButton");
    var sidebar = document.querySelector(".mobile");
    var icon = toggleButton.querySelector("i");

    // Add click event listener to the button
    toggleButton.addEventListener("click", function() {
        // Toggle the visibility of the sidebar
        if (sidebar.style.display === "none" || sidebar.style.display === "") {
            sidebar.style.display = "flex";
            sidebar.style.flexDirection = "column";
            icon.classList.remove("fa-bars-staggered");
            icon.classList.add("fa-xmark");
        } else {
            sidebar.style.display = "none";
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars-staggered");
        }
    });
</script>