// FAQ Data
const faqData = [
    {
        question: "What is TripX?",
        answer: "TripX is your personal travel companion that helps you plan and manage trips, track expenses, and store travel memories."
    },
    {
        question: "How do I create a new trip?",
        answer: "Click on the 'add trip' button and fill in your trip details including destination, dates, and other information."
    },
    {
        question: "Can I track expenses?",
        answer: "Yes! TripX includes an expense tracker to help you manage your travel budget and split costs with travel companions."
    }
];

// DOM Manipulation & Features
document.addEventListener('DOMContentLoaded', () => {
    // Popup Notification
    const popup = document.createElement('div');
    popup.className = 'popup';
    popup.innerHTML = `
        Welcome to TripX! Plan your next adventure with us. 
        <span class="close-popup">&times;</span>
    `;
    document.body.insertBefore(popup, document.body.firstChild);
    
    // Close Popup
    document.querySelector('.close-popup').onclick = () => {
        popup.style.display = 'none';
    };

    // Create FAQ Section
    const faqSection = document.createElement('div');
    faqSection.className = 'faq-section';
    faqSection.innerHTML = '<h2>Frequently Asked Questions</h2>';
    
    // Add FAQs
    const faqContainer = document.createElement('div');
    faqContainer.className = 'faq-container';
    
    faqData.forEach(faq => {
        const faqItem = document.createElement('div');
        faqItem.className = 'faq-item';
        faqItem.innerHTML = `
            <div class="faq-question">${faq.question}</div>
            <div class="faq-answer">${faq.answer}</div>
        `;
        
        faqItem.querySelector('.faq-question').onclick = () => {
            const answer = faqItem.querySelector('.faq-answer');
            answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
        };
        
        faqContainer.appendChild(faqItem);
    });
    
    faqSection.appendChild(faqContainer);
    document.body.appendChild(faqSection);
});