@extends('frontend.layouts.app')

@section('content')
    <div class="inquiry-details-container">
        <!-- Header -->
        <div class="inquiry-details-header">
            <h1>Inquiry #356</h1>
        </div>

        <!-- Tabs -->
        <div class="tabs-container">
            <div class="tab active" id="itemsTab">Items</div>
            <div class="tab" id="conversationsTab">Updates</div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Left Section: Items/Conversations -->
            <div class="content-section">
                <!-- Items Section -->
                <div class="items-section" id="itemsContent">
                    <!-- Item 1 -->
                    <div class="item-card">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/70" alt="Product Image">
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <div class="item-weight">8Tons</div>
                                <h3 class="item-title">Mix Fruit for Juice</h3>
                            </div>
                            <p class="item-category">Vegetables & Fruit</p>
                            <p class="item-description">
                                From food and beverages to raw materials and recycled goods — Tradex Aria bridges global demand
                                and supply with precision, trust, and efficiency.
                            </p>
                        </div>
                        <button class="btn btn-price">Price&nbsp;&nbsp;&nbsp;3,600 EGP</button>
                    </div>

                    <!-- Item 2 -->
                    <div class="item-card">
                        <div class="item-image">
                            <img src="https://via.placeholder.com/70" alt="Product Image">
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <div class="item-weight">8Tons</div>
                                <h3 class="item-title">Mix Fruit for Juice</h3>
                            </div>
                            <p class="item-category">Vegetables & Fruit</p>
                            <p class="item-description">
                                From food and beverages to raw materials and recycled goods — Tradex Aria bridges global demand
                                and supply with precision, trust, and efficiency.
                            </p>
                        </div>
                        <button class="btn btn-price">Price&nbsp;&nbsp;&nbsp;0 EGP</button>
                    </div>
                </div>

                <!-- Conversations Section -->
                <div class="conversations-section" id="conversationsContent" style="display: none;">
                    <div class="chat-container">
                        <!-- Messages Container -->
                        <div class="messages-container" id="messagesContainer">
                            <!-- System Message -->
                            <div class="conversation-message system-message">
                                <div class="message-content">
                                    <p class="message-text">Mokhtar Created inquiry order.</p>
                                    <span class="message-time">15 May 2025 - 08:45</span>
                                    <svg class="checkmark-icon" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M13.5 4L6 11.5L2.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Admin Message -->
                            <div class="conversation-message admin-message">
                                <div class="message-header">
                                    <span class="sender-name">Admin Gaser</span>
                                </div>
                                <div class="message-content">
                                    <p class="message-text">Hello, Mr Mokhtar<br>
                                    We are ready to make you an offer with the items you wanted, but there's something we need to discuss first.</p>
                                    <span class="message-time">15 May 2025 - 08:45</span>
                                </div>
                            </div>

                            <!-- User Message -->
                            <div class="conversation-message user-message">
                                <div class="message-header">
                                    <span class="sender-name">You</span>
                                </div>
                                <div class="message-content">
                                    <p class="message-text">Thank you for the quick response. What do you need to discuss?</p>
                                    <span class="message-time">15 May 2025 - 09:15</span>
                                </div>
                            </div>

                            <!-- Offer Message -->
                            <div class="conversation-message offer-message">
                                <div class="message-content">
                                    <p class="message-text">Offer was made for 16,500 EGP by Admin Gaser</p>
                                    <span class="message-time">15 May 2025 - 09:30</span>
                                </div>
                            </div>
                        </div>

                        <!-- Chat Input -->
                        <div class="chat-input-container">
                            <div class="chat-input-wrapper">
                                <textarea
                                    id="chatInput"
                                    class="chat-input"
                                    placeholder="Type your message here..."
                                    rows="1"
                                ></textarea>
                                <button class="send-button" id="sendButton">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M18 2L9 11M18 2L12 18L9 11M18 2L2 8L9 11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Section: Inquiry Summary -->
            <div class="summary-section">
                <div class="summary-card">
                    <h2>Inquiry Summary</h2>

                    <div class="summary-header">
                        <div class="summary-info">
                            <span class="summary-label">Inquiry Number</span>
                            <span class="summary-value">#36591</span>
                        </div>
                        <span class="status-badge ongoing">Ongoing</span>
                    </div>

                    <div class="summary-details">
                        <div class="summary-row">
                            <span class="row-label">Available products Price</span>
                            <span class="row-value">3600 EGP</span>
                        </div>
                        <div class="summary-row">
                            <span class="row-label">Taxes</span>
                            <span class="row-value">3600 EGP</span>
                        </div>
                        <div class="summary-row">
                            <span class="row-label">Delivery</span>
                            <span class="row-value">3600 EGP</span>
                        </div>
                        <div class="summary-row">
                            <span class="row-label">Discount</span>
                            <span class="row-value">3600 EGP</span>
                        </div>
                        <div class="summary-row">
                            <span class="row-label">Extra fees</span>
                            <span class="row-value">3600 EGP</span>
                        </div>
                    </div>

                    <div class="summary-total">
                        <span class="total-label">Total</span>
                        <span class="total-value">3600 EGP</span>
                    </div>

                    <button class="btn btn-accept">Accept Offer</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Container */
        .inquiry-details-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 48px 16px;
            margin-top: 100px;
        }

        /* Header */
        .inquiry-details-header {
            margin-bottom: 30px;
        }

        .inquiry-details-header h1 {
            font-size: 36px;
            font-weight: bold;
            color: #111827;
            margin: 0;
        }

        /* Tabs */
        .tabs-container {
            display: flex;
            gap: 0;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
        }

        .tab {
            padding: 12px 200px;
            font-size: 15px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #0891b2;
            border-bottom-color: #0891b2;
        }

        .tab:hover {
            color: #0891b2;
        }

        /* Main Content */
        .main-content {
            display: flex;
            gap: 30px;
            align-items: flex-start;
        }

        /* Content Section */
        .content-section {
            flex: 1;
        }

        /* Items Section */
        .items-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Item Card */
        .item-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            align-items: flex-start;
            gap: 16px;
        }

        .item-image {
            width: 70px;
            height: 70px;
            border-radius: 6px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .item-details {
            flex: 1;
        }

        .item-header {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 4px;
        }

        .item-weight {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .item-title {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .item-category {
            font-size: 12px;
            color: #0891b2;
            margin: 0 0 8px 0;
        }

        .item-description {
            font-size: 11px;
            color: #6b7280;
            line-height: 1.6;
            margin: 0;
        }

        /* Chat Container */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 600px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        /* Messages Container */
        .messages-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: #f3f4f6;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 3px;
        }

        .messages-container::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }

        /* Conversations Section */
        .conversations-section {
            display: flex;
            flex-direction: column;
        }

        .conversation-message {
            border-radius: 8px;
            padding: 16px 20px;
            max-width: 70%;
            animation: messageSlide 0.3s ease;
        }

        @keyframes messageSlide {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* System Message */
        .system-message {
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            align-self: center;
            max-width: 90%;
        }

        .system-message .message-content {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
        }

        .system-message .message-text {
            flex: 1;
            font-size: 14px;
            color: #111827;
            margin: 0;
            text-align: center;
        }

        .system-message .message-time {
            font-size: 11px;
            color: #6b7280;
        }

        .checkmark-icon {
            color: #10b981;
            flex-shrink: 0;
        }

        /* Admin Message */
        .admin-message {
            background: #0891b2;
            color: white;
            align-self: flex-start;
        }

        .message-header {
            margin-bottom: 8px;
        }

        .sender-name {
            font-size: 14px;
            font-weight: 600;
        }

        .admin-message .message-text {
            font-size: 13px;
            line-height: 1.6;
            margin: 0 0 8px 0;
        }

        .admin-message .message-time {
            font-size: 11px;
            opacity: 0.9;
        }

        /* User Message */
        .user-message {
            background: #e0f2fe;
            color: #0c4a6e;
            align-self: flex-end;
        }

        .user-message .message-header {
            margin-bottom: 8px;
        }

        .user-message .sender-name {
            font-size: 14px;
            font-weight: 600;
        }

        .user-message .message-text {
            font-size: 13px;
            line-height: 1.6;
            margin: 0 0 8px 0;
        }

        .user-message .message-time {
            font-size: 11px;
            opacity: 0.8;
        }

        /* Offer Message */
        .offer-message {
            background: #1e3a4c;
            color: white;
            text-align: center;
            align-self: center;
            max-width: 90%;
        }

        .offer-message .message-content {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .offer-message .message-text {
            font-size: 13px;
            font-weight: 500;
            margin: 0;
        }

        .offer-message .message-time {
            font-size: 11px;
            opacity: 0.8;
        }

        /* Chat Input Container */
        .chat-input-container {
            border-top: 1px solid #e5e7eb;
            padding: 16px 20px;
            background: #f9fafb;
        }

        .chat-input-wrapper {
            display: flex;
            gap: 12px;
            align-items: flex-end;
        }

        .chat-input {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            padding: 10px 16px;
            font-size: 14px;
            font-family: inherit;
            resize: none;
            max-height: 120px;
            min-height: 40px;
            background: white;
            transition: border-color 0.3s ease;
        }

        .chat-input:focus {
            outline: none;
            border-color: #0891b2;
        }

        .chat-input::placeholder {
            color: #9ca3af;
        }

        .send-button {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #0891b2;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .send-button:hover {
            background: #0e7490;
            transform: scale(1.05);
        }

        .send-button:active {
            transform: scale(0.95);
        }

        .send-button:disabled {
            background: #d1d5db;
            cursor: not-allowed;
            transform: none;
        }

        /* Summary Section */
        .summary-section {
            width: 350px;
            flex-shrink: 0;
        }

        .summary-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 24px;
        }

        .summary-card h2 {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
            margin: 0 0 20px 0;
        }

        .summary-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-info {
            display: flex;
            flex-direction: row;
            background: #0585bc;
            padding: 12px 16px;
            border-radius: 40px;
            align-items: center;
            gap: 6px;
        }

        .summary-label {
            font-size: 11px;
            color: #ffffff;
        }

        .summary-value {
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.ongoing {
            background-color: #ef4444;
            color: white;
        }

        .summary-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row-label {
            font-size: 13px;
            color: #6b7280;
        }

        .row-value {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }

        .total-label {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
        }

        .total-value {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
        }

        /* Buttons */
        .btn {
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.3s ease;
            align-self: flex-start;
        }

        .btn-price {
            background-color: #0891b2;
            color: white;
            margin-top: 0;
        }

        .btn-price:hover {
            background-color: #0e7490;
        }

        .btn-accept {
            width: 100%;
            background-color: #0891b2;
            color: white;
            padding: 12px;
            font-size: 14px;
        }

        .btn-accept:hover {
            background-color: #0e7490;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-content {
                flex-direction: column;
            }

            .summary-section {
                width: 100%;
            }

            .tab {
                padding: 12px 80px;
            }

            .conversation-message {
                max-width: 85%;
            }
        }

        @media (max-width: 768px) {
            .tab {
                padding: 12px 40px;
            }

            .chat-container {
                height: 500px;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const itemsTab = document.getElementById('itemsTab');
            const conversationsTab = document.getElementById('conversationsTab');
            const itemsContent = document.getElementById('itemsContent');
            const conversationsContent = document.getElementById('conversationsContent');
            const chatInput = document.getElementById('chatInput');
            const sendButton = document.getElementById('sendButton');
            const messagesContainer = document.getElementById('messagesContainer');

            // Items tab click
            itemsTab.addEventListener('click', function() {
                itemsTab.classList.add('active');
                conversationsTab.classList.remove('active');
                itemsContent.style.display = 'flex';
                conversationsContent.style.display = 'none';
            });

            // Conversations tab click
            conversationsTab.addEventListener('click', function() {
                conversationsTab.classList.add('active');
                itemsTab.classList.remove('active');
                conversationsContent.style.display = 'flex';
                itemsContent.style.display = 'none';
            });

            // Auto-resize textarea
            chatInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });

            // Send message on button click
            sendButton.addEventListener('click', sendMessage);

            // Send message on Enter (Shift+Enter for new line)
            chatInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });

            function sendMessage() {
                const messageText = chatInput.value.trim();

                if (messageText === '') return;

                // Get current time
                const now = new Date();
                const timeString = now.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) +
                                 ' - ' +
                                 now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });

                // Create user message element
                const messageDiv = document.createElement('div');
                messageDiv.className = 'conversation-message user-message';
                messageDiv.innerHTML = `
                    <div class="message-header">
                        <span class="sender-name">You</span>
                    </div>
                    <div class="message-content">
                        <p class="message-text">${messageText.replace(/\n/g, '<br>')}</p>
                        <span class="message-time">${timeString}</span>
                    </div>
                `;

                // Add message to container
                messagesContainer.appendChild(messageDiv);

                // Clear input
                chatInput.value = '';
                chatInput.style.height = 'auto';

                // Scroll to bottom
                messagesContainer.scrollTop = messagesContainer.scrollHeight;

                // Simulate admin response after 2 seconds
                setTimeout(simulateAdminResponse, 2000);
            }

            function simulateAdminResponse() {
                const responses = [
                    "Thank you for your message. I'll look into this right away.",
                    "I understand your request. Let me check with our team.",
                    "Great! I'll prepare the updated offer for you.",
                    "I've received your message. We'll get back to you shortly.",
                    "Perfect! Let me process this information."
                ];

                const randomResponse = responses[Math.floor(Math.random() * responses.length)];

                const now = new Date();
                const timeString = now.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' }) +
                                 ' - ' +
                                 now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit' });

                const messageDiv = document.createElement('div');
                messageDiv.className = 'conversation-message admin-message';
                messageDiv.innerHTML = `
                    <div class="message-header">
                        <span class="sender-name">Admin Gaser</span>
                    </div>
                    <div class="message-content">
                        <p class="message-text">${randomResponse}</p>
                        <span class="message-time">${timeString}</span>
                    </div>
                `;

                messagesContainer.appendChild(messageDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        });
    </script>
@endsection
