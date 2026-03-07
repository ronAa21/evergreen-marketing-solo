// 1. Toggle the 'Cards' Dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('cardsDropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// 2. Toggle the Profile/Account Dropdown
function toggleProfileDropdown(e) {
    e.stopPropagation();
    const dd = document.getElementById('profileDropdown');
    const btn = document.getElementById('profileBtn');
    const isOpen = dd.classList.toggle('show');
    btn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
}

// 3. Global Click Listener to close menus when clicking outside
window.addEventListener('click', function(event) {
    // Close Cards Dropdown
    if (!event.target.matches('.dropbtn')) {
        const dropdown = document.getElementById('cardsDropdown');
        if (dropdown && dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
        }
    }

    // Close Profile Dropdown
    const dd = document.getElementById('profileDropdown');
    const btn = document.getElementById('profileBtn');
    if (dd && dd.classList.contains('show') && !event.composedPath().includes(dd) && event.target !== btn) {
        dd.classList.remove('show');
        btn.setAttribute('aria-expanded', 'false');
    }
});

// 4. Sign Out Modal Logic
function showSignOutModal(event) {
    event.preventDefault();
    
    const modal = document.createElement('div');
    // ... (CSS for modal omitted for brevity, but this triggers the confirm)
    
    // Key logic for actual sign out:
    const confirmBtn = modal.querySelector('#confirmBtn'); // inside your modal template
    if (confirmBtn) {
        confirmBtn.onclick = () => window.location.href = '../logout.php';
    }
}

function toggleProfileDropdown(e) {
    // Stops the click from immediately closing the menu via the window listener
    e.stopPropagation(); 
    
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('show');
}

// Close the dropdown if the user clicks anywhere else on the screen
window.onclick = function(event) {
    const dropdown = document.getElementById('profileDropdown');
    if (dropdown.classList.contains('show')) {
        if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-btn img')) {
            dropdown.classList.remove('show');
        }
    }
}

function showSignOutModal(event) {
    event.preventDefault();
    
    // Create modal overlay
    const modal = document.createElement('div');
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 54, 49, 0.8);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        animation: fadeIn 0.2s ease;
    `;
    
    // Create modal content
    modal.innerHTML = `
        <style>
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes slideUp {
                from { 
                    opacity: 0;
                    transform: translateY(20px);
                }
                to { 
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Image Icon */
            img {
            width: 55px;
            height: 50px;
            margin-bottom:5px;
            }
        </style>
        <div style="
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 420px;
            width: 90%;
            text-align: center;
            animation: slideUp 0.3s ease;
        ">
            <div style="
                width: 90px;
                height: 90px;
                background: linear-gradient(135deg, #003631 0%, #1a6b62 100%);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-direction: start;
                margin: 0 auto 2.5rem;
                font-size: 2rem;
            "><img src="../images/warning.png"></div>
            
            <h3 style="
                color: #003631;
                margin-bottom: 0.75rem;
                font-size: 1.75rem;
                font-weight: 600;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            ">Sign Out</h3>
            
            <p style="
                color: #666;
                margin-bottom: 2rem;
                font-size: 1rem;
                line-height: 1.6;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            ">Are you sure you want to sign out of your account?</p>
            
            <div style="
                display: flex;
                gap: 1rem;
                justify-content: center;
            ">
                <button id="cancelBtn" style="
                    padding: 0.85rem 2rem;
                    background: transparent;
                    color: #003631;
                    border: 2px solid #003631;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 0.95rem;
                    transition: all 0.3s ease;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                ">Cancel</button>
                
                <button id="confirmBtn" style="
                    padding: 0.85rem 2rem;
                    background: #003631;
                    color: white;
                    border: 2px solid #003631;
                    border-radius: 8px;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 0.95rem;
                    transition: all 0.3s ease;
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                ">Sign Out</button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    // Get buttons
    const cancelBtn = modal.querySelector('#cancelBtn');
    const confirmBtn = modal.querySelector('#confirmBtn');
    
    // Add hover effects for Cancel button
    cancelBtn.onmouseover = () => {
        cancelBtn.style.background = '#f5f5f5';
        cancelBtn.style.borderColor = '#003631';
        cancelBtn.style.transform = 'translateY(-2px)';
    };
    cancelBtn.onmouseout = () => {
        cancelBtn.style.background = 'transparent';
        cancelBtn.style.transform = 'translateY(0)';
    };
    
    // Add hover effects for Confirm button
    confirmBtn.onmouseover = () => {
        confirmBtn.style.background = '#F1B24A';
        confirmBtn.style.borderColor = '#F1B24A';
        confirmBtn.style.color = '#003631';
        confirmBtn.style.transform = 'translateY(-2px)';
        confirmBtn.style.boxShadow = '0 4px 12px rgba(241, 178, 74, 0.3)';
    };
    confirmBtn.onmouseout = () => {
        confirmBtn.style.background = '#003631';
        confirmBtn.style.borderColor = '#003631';
        confirmBtn.style.color = 'white';
        confirmBtn.style.transform = 'translateY(0)';
        confirmBtn.style.boxShadow = 'none';
    };
    
    // Handle button clicks
    cancelBtn.onclick = () => document.body.removeChild(modal);
    confirmBtn.onclick = () => window.location.href = '../logout.php';
    
    // Close on outside click
    modal.onclick = (e) => {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    };
    
    // Close on Escape key
    const handleEscape = (e) => {
        if (e.key === 'Escape' && document.body.contains(modal)) {
            document.body.removeChild(modal);
            document.removeEventListener('keydown', handleEscape);
        }
    };
    document.addEventListener('keydown', handleEscape);
}

// fetch content data from database
async function getContents() {
  try {
    // Correct path to Admin-side backend (go up two levels, then into Admin-side)
    const response = await fetch("../Admin-side/backend/actions.php", {
        method: "GET"
    });

    const data = await response.json();
    console.log(data);
    
    // Display the content
    displayContents(data);
  } catch (error) {
    alert('Something went wrong')
    console.error("Path error or server error:", error);
  }
}

// Display content on the page
function displayContents(contents) {
  const contentView = document.querySelector('.content-view');
  
  // Clear existing content
  contentView.innerHTML = '';
  
  if (contents.length === 0) {
    contentView.innerHTML = '<p style="text-align: center; color: #666; margin-top: 2rem;">No content available yet.</p>';
    return;
  }
  
  // Display each content item
  contents.forEach(item => {
    const contentDiv = document.createElement('div');
    contentDiv.style.cssText = `
      background: white;
      border-radius: 10px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      border-left: 4px solid #003631;
      max-width: 800px;
      margin-left: auto;
      margin-right: auto;
    `;
    
    contentDiv.innerHTML = `
      <h2 style="color: #003631; margin-bottom: 1rem; font-size: 1.8rem;">${item.title}</h2>
      <p style="color: #666; line-height: 1.6; margin-bottom: 1rem;">${item.body}</p>
      <small style="color: #999; font-size: 0.9rem;">
        Published: ${new Date(item.created_at).toLocaleDateString()}
      </small>
    `;
    
    contentView.appendChild(contentDiv);
  });
}

// Load content when page loads
document.addEventListener('DOMContentLoaded', getContents);