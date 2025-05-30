        <?php if (isLoggedIn()): ?>
        <div class="ai-chat-container" id="aiChatContainer">
            <div class="ai-chat-header">
                <h3><i class="fas fa-robot"></i> Finance Assistant</h3>
                <button class="toggle-chat" id="toggleChat">
                    <i class="fas fa-comments"></i>
                </button>
            </div>
            <div class="ai-chat-body" id="aiChatBody">
                <div class="chat-messages" id="chatMessages"></div>
                <div class="chat-input">
                    <input type="text" id="userMessage" placeholder="Ask about finance, budgeting, or investments...">
                    <button id="sendMessage"><i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
        <script src="assets/js/app.js"></script>
    </div>
</body>
</html>