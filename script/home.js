$(document).ready(function () {
    loadPosts(0);
    $(document).on("click", "[id^='add-button']", function(event) {
        event.preventDefault();
        var postId = $(this).attr('id').replace('add-button', '');
        console.log(postId);
        addToCart(postId);  
    });
    $(document).on("click", ".delete_post_btn", function(event) {
        event.preventDefault(); // Prevent the default behavior
        var postId = $(this).attr('id').replace('bucket-button', '');
        console.log(postId);
        deleteFromCart(postId);  
        });
    $(document).on('click', '.posts-container .post', function() {
        var postId = $(this).data('postid');
        var url = "item.php?id=" + postId;
        window.location.href = url;
    });
       
    $('#load-more-btn').on('click', function () {
        var displayedPosts = $('.posts-container .post').length;
        loadPosts(displayedPosts); // Load more posts on button click
    });
});
function addToCart(postId) {
    // AJAX request to update cart
    $.ajax({
        type: "POST",
        url: "../addtocart.php", 
        data: { postId: postId},
        success: function(response) {
            // Parse the JSON response
            var jsonResponse = JSON.parse(response);
            
            // Check if the request was successful
            if (jsonResponse.success) {
                alert("Item added to Bucket List successfully");
                window.location.href = '/home.php'; 
            } else {
                // Check if the error is due to a duplicate entry
                if (jsonResponse.error.includes("Duplicate entry")) {
                    alert("This item is already in your Bucket!");
                    window.location.href = '/home.php'; 
                } else {
                    // Display generic error message
                    console.error(jsonResponse.error);
                }
            }
        },
        error: function(xhr, status, error) {
            // Handle error (if needed)
            console.error(xhr.responseText);
        }
    });
}
  function loadPosts(offset) {
    $.ajax({
        url: '../PostLoader.php',
        method: 'GET',
        data: { offset: offset },
        success: function (response) {
            var posts = JSON.parse(response);
            console.log(posts);
            if (posts.error) {
                console.error(posts.error);
                return;
            }
            posts.forEach(function (post) {
                var postHTML = `
                <div class="post" data-postid="${post.book_id}">
                <div class="post-image-div">
                    <img class="post-image" src="${post.poster_image}" alt="Post Image">
                </div>
                <div class="post-content">
        <!-- Post title and Rs option -->
        <div class="post-details">
        <span class="rs-option"> ${post.publication_date}</span>
            <span class="post-title">${post.book_title}</span>
            <span class="rs-option"> ${post.genre}</span>
            <span class="rs-option"> ${post.ratings}</span>
            <span class="rs-option"> ${post.category}</span>
        </div>
        <button class="add-to-cart-btn" id="add-button${post.book_id}">Add to cart</button>
        <a class="delete_post_btn" id="bucket-button${post.book_id}"><i class="fa-solid fa-trash"></i></a>
    </div>
            </div>`            
                $('.posts-container').append(postHTML);
            });
  
            if (posts.length >= 9) {
                $('.button-container').show();
            } else {
                $('.button-container').hide();
            }
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
  }
 
  
function deleteFromCart(postId) {
    // Show confirmation dialog
    if (confirm('Are you sure you want to delete this item from your cart?')) {
        // If user confirms, send AJAX request to delete the item
        $.ajax({
            url: '../delete-from-cart.php',
            type: 'POST',
            data: { post_id: postId },
            success: function(response) {
                // If deletion is successful, reload the cart page
                window.location.href = '/home.php';
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    } else {
        // If user cancels, do nothing
        console.log('Deletion canceled');
    }
}