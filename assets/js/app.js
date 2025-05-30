document.addEventListener('DOMContentLoaded', function() {
    // Toggle AI chat
    const toggleChat = document.getElementById('toggleChat');
    const aiChatContainer = document.getElementById('aiChatContainer');
    const aiChatBody = document.getElementById('aiChatBody');
    
    if (toggleChat && aiChatContainer) {
        toggleChat.addEventListener('click', function(e) {
            e.stopPropagation();
            aiChatContainer.classList.toggle('open');
        });
        
        aiChatContainer.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        document.addEventListener('click', function() {
            aiChatContainer.classList.remove('open');
        });
    }
    
    // AI Chat functionality
    const chatMessages = document.getElementById('chatMessages');
    const userMessageInput = document.getElementById('userMessage');
    const sendMessageBtn = document.getElementById('sendMessage');
    
    if (chatMessages && userMessageInput && sendMessageBtn) {
        // Add welcome message
        addAIMessage("Hello! I'm your finance assistant. Ask me about budgeting, saving money, or investments.");
        
        sendMessageBtn.addEventListener('click', sendMessage);
        userMessageInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
    
    function sendMessage() {
        const message = userMessageInput.value.trim();
        if (message === '') return;
        
        // Add user message to chat
        addUserMessage(message);
        userMessageInput.value = '';
        
        // Show loading indicator
        const loadingId = 'loading-' + Date.now();
        chatMessages.innerHTML += `
            <div id="${loadingId}" class="chat-message ai-message">
                <i class="fas fa-spinner fa-spin"></i> Thinking...
            </div>
        `;
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // Send to server
        fetch('api/gemini.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            // Remove loading indicator
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) {
                loadingElement.remove();
            }
            
            // Add AI response
            if (data.response) {
                addAIMessage(data.response);
            } else {
                addAIMessage("Sorry, I couldn't process your request. Please try again.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const loadingElement = document.getElementById(loadingId);
            if (loadingElement) {
                loadingElement.remove();
            }
            addAIMessage("There was an error connecting to the AI service. Please try again later.");
        });
    }
    
    function addUserMessage(message) {
        chatMessages.innerHTML += `
            <div class="chat-message user-message">
                ${message}
            </div>
        `;
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function addAIMessage(message) {
        chatMessages.innerHTML += `
            <div class="chat-message ai-message">
                ${message}
            </div>
        `;
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Form validation for add transaction
    const transactionForm = document.querySelector('.transaction-form');
    if (transactionForm) {
        transactionForm.addEventListener('submit', function(e) {
            const amount = parseFloat(this.querySelector('#amount').value);
            const type = this.querySelector('#type').value;
            const category = this.querySelector('#category').value;
            
            if (isNaN(amount) || amount <= 0) {
                e.preventDefault();
                alert('Please enter a valid amount greater than 0');
                return;
            }
            
            if (!type) {
                e.preventDefault();
                alert('Please select a transaction type');
                return;
            }
            
            if (!category) {
                e.preventDefault();
                alert('Please select a category');
                return;
            }
        });
    }
});