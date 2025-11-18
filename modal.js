document.addEventListener('DOMContentLoaded', () => {
    // --- 1. Get DOM elements ---
    
    // Zoom Modal
    const modal = document.getElementById('food-modal');
    const closeBtn = modal.querySelector('.close-btn');
    const orderNowBtn = modal.querySelector('.btn.primary'); 
    
    const modalImg = document.getElementById('modal-food-img');
    const modalName = document.getElementById('modal-food-name');
    const modalPrice = document.getElementById('modal-food-price');
    const modalStars = document.getElementById('modal-food-stars');
    const modalDiscount = document.getElementById('modal-food-discount');

    // Order Modal
    const orderModal = document.getElementById('order-modal');
    const orderCloseBtn = orderModal.querySelector('.order-close-btn');
    const orderForm = document.getElementById('order-form');
    const orderItemNameSpan = document.getElementById('order-item-name');
    const confirmPayButton = orderForm.querySelector('.btn.primary'); 

    const menuCards = document.querySelectorAll('.menu-card');
    let currentItemName = ''; // Variable to hold the name of the food being ordered

    // --- 2. Function to open the Food Zoom Modal ---
    const openModal = (card) => {
        // Extract data
        const name = card.querySelector('.card-content h3').textContent;
        const img = card.querySelector('.food-img').src;
        const price = card.querySelector('.price').textContent;
        const stars = card.querySelector('.stars').textContent;
        const discountElement = card.querySelector('.discount');
        const discount = discountElement ? discountElement.textContent : 'No Discount';
        
        // Store item name globally for the Order Modal
        currentItemName = name; 

        // Populate the zoom modal
        modalImg.src = img;
        modalName.textContent = name;
        modalPrice.textContent = price;
        modalStars.textContent = stars;
        
        if (discount !== 'No Discount') {
            modalDiscount.textContent = discount;
            modalDiscount.style.display = 'inline-block';
        } else {
            modalDiscount.style.display = 'none';
        }

        // Display the zoom modal
        modal.style.display = 'flex'; 
    };

    // --- 3. Click Handler for Menu Cards (Open Zoom Modal) ---
    menuCards.forEach(card => {
        card.addEventListener('click', (event) => {
            const isButtonOrHeart = event.target.classList.contains('add-btn') || 
                                    event.target.classList.contains('heart');
            
            if (isButtonOrHeart) {
                event.stopPropagation();
                return;
            }
            
            openModal(card);
        });
    });

    // --- 4. Handler for "Order Now" Button (Switch to Order Modal) ---
    orderNowBtn.addEventListener('click', () => {
        // 1. Close the current zoom modal
        modal.style.display = 'none';

        // 2. Populate the order modal with the item name
        orderItemNameSpan.textContent = currentItemName;

        // 3. Open the order modal
        orderModal.style.display = 'flex'; 
    });

    // --- 5. AJAX Form Submission Handler (with Anti-Duplication Fix) ---
    orderForm.addEventListener('submit', (event) => {
        // 1. CRITICAL: Stop the default HTML submission immediately.
        event.preventDefault(); 
        
        // Disable the button to prevent double-clicks
        confirmPayButton.disabled = true; 
        confirmPayButton.textContent = 'Processing...';

        // 2. Collect data from the form
        const orderData = {
            item_name: orderItemNameSpan.textContent,
            quantity: document.getElementById('order-quantity').value,
            customer_name: document.getElementById('order-name').value,
            phone: document.getElementById('order-phone').value,
            address: document.getElementById('order-address').value
        };

        // 3. Send data using AJAX (Fetch API) to the PHP script
        fetch('process_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            // Convert JS object to URL-encoded format for PHP $_POST access
            body: new URLSearchParams(orderData).toString() 
        })
        .then(response => response.json())
        .then(data => {
            // Re-enable the button regardless of success/failure
            confirmPayButton.disabled = false;
            confirmPayButton.textContent = 'Confirm & Pay';

            // 4. Handle the response from the PHP script
            if (data.success) {
                alert(`Success! ${data.message}`);
                orderForm.reset(); 
                orderModal.style.display = 'none'; 
            } else {
                alert(`Order Failed: ${data.message}`);
                console.error('Server error:', data.message);
            }
        })
        .catch(error => {
            // Handle connection or network errors
            confirmPayButton.disabled = false;
            confirmPayButton.textContent = 'Confirm & Pay';
            alert('An unexpected error occurred while placing the order.');
            console.error('Fetch Error:', error);
        });
    });

    // --- 6. Modal Closing Logic ---
    
    // Close Zoom Modal
    closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    
    // Close Order Modal
    orderCloseBtn.addEventListener('click', () => {
        orderModal.style.display = 'none';
    });

    // Close Modals when user clicks anywhere outside
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
        if (event.target === orderModal) {
            orderModal.style.display = 'none';
        }
    });
});