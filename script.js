const chatBody = document.querySelector(".chat-body");
const messageInput = document.querySelector(".message-input");
const sendMessage = document.querySelector("#send-message");
const fileInput = document.querySelector("#file-input");
const fileUploadWrapper = document.querySelector(".file-upload-wrapper");
const fileCancelButton = fileUploadWrapper.querySelector("#file-cancel");
const chatbotToggler = document.querySelector("#chatbot-toggler");
const closeChatbot = document.querySelector("#close-chatbot");

// API setup
const API_KEY = "AIzaSyA9e3v7EpHrApXU0K1O6bAA-hBQVmWfTkM";
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=${API_KEY}`;

// Initialize user message and file data
const userData = {
  message: null,
  file: {
    data: null,
    mime_type: null,
  },
};

// Store chat history
const chatHistory = [];
const initialInputHeight = messageInput.scrollHeight;

// Create message element with dynamic classes and return it
const createMessageElement = (content, ...classes) => {
  const div = document.createElement("div");
  div.classList.add("message", ...classes);
  div.innerHTML = content;
  return div;
};

// Generate bot response using API
const generateBotResponse = async (incomingMessageDiv) => {
  const messageElement = incomingMessageDiv.querySelector(".message-text");

  // Add user message to chat history
  chatHistory.push({
    role: "user",
    parts: [{ text: userData.message }, ...(userData.file.data ? [{ inline_data: userData.file }] : [])],
  });

  // Define persistent business memory (long-term memory)
  const businessMemory = [
  {
    role: "user",
    parts: [{
      text: `Context:
Cloud Technology Computing is a technology company that provides:
- Custom Website & App Development (HTML, CSS, JavaScript, PHP, MySQL)
- Cloud Migration & Hybrid Cloud Solutions (AWS, Azure, IBM Cloud)
- AI-powered AI Chatbot Development (Gemini, IBM Watson)
- Technical SEO, Google Analytics, and PageSpeed Optimization
- Ecommerce Integration with Stripe & PayPal
- SAP Analytics Cloud & Data Dashboards

We work with small businesses to scale and automate their operations.
Cloud Technology Computing
Entry Level services
Cloud Technology
Computing Business services
PRESIDENT/C.E.O Jhon Arzu-Gil
Cloud Technology Computing
Schedule an appointment and check out a list of our services. The affordable solutions are
priced way below market average to better target cash-strapped small business owners and
future entrepreneurs who dream about starting their own company but don't have the necessary
resources or knowledge to do it. That's where Cloud Technology Computing Corporation comes
in we have a proven track record of Consulting Small Businesses who aren’t tech-savvy and our
services still end up paying for themselves once they factor in return on investment.
https://cloudtechnologycomputing.com

Affordable 3-Page Website Development
Establishing an online presence is crucial in today
's digital landscape. Our 3-page website
development service ensures you have a professional platform to showcase your brand, whether
it's a blog to engage followers or an e-commerce site to drive sales. Our process includes
domain name registration, custom design, and full site deployment, all starting at $1,000.
Key Features:
Domain Registration: Secure a unique domain that represents your brand.
Custom Design: Tailored layouts and aesthetics to align with your brand identity.
Responsive Development: Optimized for various devices, ensuring a seamless user
experience.
Investing in a professionally designed website can significantly boost your credibility and
accessibility in the market.
Starting at 1000$
Affordable Local S.E.O Package
Local Search Engine Optimization (SEO) is vital for businesses aiming to attract customers
within a specific geographic area. Our Local SEO package, starting at $500 per month, focuses
on enhancing your visibility in local search results, thereby driving more foot traffic and local
online inquiries.
Key Components:
Google Business Profile Optimization: We create and optimize your Google Business
Profile, a critical tool for appearing in local search results and Google's Local Pack.

Local Citations: Ensure your business information is consistently listed across various
online directories.
Review Management: Implement strategies to encourage positive customer reviews,
building trust and enhancing local rankings.
Effective local SEO strategies enable small businesses to compete with larger chains by
increasing local visibility.
Starting at 500$ A Month
Affordable Website Optimization
Website loading speed directly impacts user experience and search engine rankings. Our
website optimization service, starting at $1,500, focuses on enhancing your site's performance
to meet industry standards.
services Offered:
Performance Analysis: Comprehensive assessment of current loading speeds and
performance bottlenecks.
Code Optimization: Refinement of website code to improve efficiency and reduce load
times.
Image and Asset Compression: Optimization of media files to ensure faster loading
without compromising quality.
Optimizing your website's speed is crucial, as users often abandon sites that take longer than
three seconds to load.
Starting at 1500$
Affordable Social Media Marketing
Social media platforms are powerful tools for brand awareness and customer engagement. Our
social media marketing services, starting at $399 per month, are designed to establish and grow
your presence on platforms like Facebook.
services Include:
Profile Creation and Optimization: Set up and enhance your Facebook Business Page
to reflect your brand accurately.
Content Strategy: Develop and implement content plans that resonate with your target
audience.
Advertising Campaigns: Manage targeted ad campaigns to increase reach and
engagement.
With individuals spending a significant portion of their internet time on social media, a robust
presence can substantially boost your brand's visibility.
Starting at 399$ A Month
Affordable PPC Online Advertising
Pay-Per-Click (PPC) advertising is an effective method to drive immediate traffic to your
website. Our PPC services, starting at $450 per month, involve creating and managing
campaigns that position your ads at the top of search engine results.
services Include:
Account Setup: Establish and configure your Google Ads account.
Keyword Research: Identify and target keywords relevant to your business.
Ad Creation and Optimization: Develop compelling ads and continually optimize
them for better performance.
Leveraging PPC advertising can significantly enhance your online visibility and attract potential
customers actively searching for your products or services.
Starting at 450$ A Month
Custom Software Development
Utilizing the Agile methodology, we offer custom software development services tailored to
your business needs. This approach emphasizes iterative progress and collaboration, ensuring
the final product aligns with your objectives.
services Include:
Requirement Analysis: Understand and document your specific software needs.
Development: Iterative coding and testing to build functional software.
Deployment and Support: Launch the software and provide ongoing support to ensure
optimal performance.
Custom software solutions can streamline operations and provide a competitive edge in your
industry.
Prices vary
Web Application Development
With a foundation in core technologies like HTML, CSS, JavaScript, SQL, and PHP , we develop
robust web applications that cater to your business requirements. Our expertise ensures
scalable and efficient solutions.
services Offered:
Full-Stack Development: Handle both front-end and back-end development tasks.
Database Management: Design and manage databases for optimal data storage and
retrieval.
API Integration: Incorporate third-party services to enhance functionality.
A well-developed web application can improve user engagement and operational efficiency.
Prices vary
Mobile Application Development
Recognizing the growing trend of mobile internet usage, we offer mobile application
development services to ensure your business remains accessible on-the-go. Our native
development approach guarantees high performance and user satisfaction.
services Include:
Platform Selection: Develop applications for iOS, Android, or both, based on your
target audience.
UI/UX Design: Create intuitive interfaces that enhance user experience.
App Deployment: Manage the submission and approval process on respective app
stores.
A mobile application can significantly broaden your reach and provide customers with
convenient access to your services.
Prices vary
WordPress Development
Cloud Technology Computing specializes in custom WordPress development, providing services
that enhance website performance, functionality, and user experience. Their expertise includes:
Custom PHP Development: Tailoring WordPress functionalities to meet specific client
requirements.
Responsive Design: Ensuring websites are mobile-friendly and adapt seamlessly across
devices.
Website Migration: Handling domain transfers, DNS configurations, and email setups,
including experience with Zoho Mail.
Performance Optimization: Implementing strategies to improve website loading
speeds, ensuring compliance with speed tests.
Troubleshooting and Maintenance: Providing ongoing support to address issues and
perform necessary updates.
Plugin Management and CDN Integration: Utilizing Content Delivery Networks like
Cloudflare and services from providers such as Liquid Web to enhance website speed and
security.
These services are designed to provide clients with robust and efficient WordPress solutions.
Prices vary
SAP Consulting
I have five years of experience at IBM in the enterprise architecture department and I bring that
to Cloud Technology Computing assisting clients in selecting, implementing, and supporting
SAP solutions. Their SAP consulting services encompass:
Data Analytics: Leveraging artificial intelligence and machine learning tools to
transform large datasets into actionable insights.
Custom Analytical Applications: Developing tailored applications to facilitate
informed business decisions.
Knowledge Transfer: Conducting in-depth consultations and frequent knowledge
transfers to empower clients in effectively integrating SAP technology into their
business environments.
These services aim to optimize business processes and enhance decision-making capabilities.
Prices vary
Cloud Consulting
Drawing from experience in a Big Tech company
's data analytics department, Cloud Technology
Computing offers cloud consulting services that include:
Open-Source Software Utilization: Employing tools like Python and Jupyter
notebooks for data analysis and application development.
Proprietary Applications: Implementing solutions such as SAP Analytics Cloud,
focusing on concepts like Master Data and Transaction Data.
Hands-On Project Experience: Applying practical experience to deliver effective cloud
solutions tailored to client needs.
These services are designed to help businesses leverage cloud technologies for enhanced
operational efficiency.
Prices vary
IT Consulting
With over five years of consulting experience, including three years at a Fortune 500 Big Tech
firm, Cloud Technology Computing offers IT consulting services characterized by:
Extensive Certification: Holding 28 certifications in various technologies,
demonstrating a commitment to continuous learning and expertise.
Comprehensive Consulting: Providing insights and solutions across a wide array of IT
domains to address diverse business challenges.
These services aim to equip clients with the knowledge and tools necessary to navigate the
rapidly evolving technology landscape.
Prices vary
Pricing
Pricing for all services varies based on the specific requirements and scope of each project.
Prospective clients are encouraged to contact Cloud Technology Computing directly to discuss
their needs and obtain a customized quote.
Conclusion
Cloud Technology Computing offers a comprehensive suite of services, including WordPress
development, SAP consulting, cloud consulting, and IT consulting. Their expertise and
commitment to continuous learning position them as a valuable partner for businesses seeking
to enhance their technological capabilities.`
    }]
  }
];

  // API request options with memory injection
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
      contents: [...businessMemory, ...chatHistory], // Preload memory + history
    }),
  };

  try {
    // Fetch bot response from Gemini API
    const response = await fetch(API_URL, requestOptions);
    const data = await response.json();
    if (!response.ok) throw new Error(data.error.message);

    // Extract and display bot's response text
    const apiResponseText = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, "$1").trim();
    messageElement.innerText = apiResponseText;

    // Add bot response to chat history
    chatHistory.push({
      role: "model",
      parts: [{ text: apiResponseText }],
    });
  } catch (error) {
    console.error(error);
    messageElement.innerText = error.message;
    messageElement.style.color = "#ff0000";
  } finally {
    userData.file = {};
    incomingMessageDiv.classList.remove("thinking");
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });
  }
};


// Handle outgoing user messages
const handleOutgoingMessage = (e) => {
  e.preventDefault();
  userData.message = messageInput.value.trim();
  messageInput.value = "";
  messageInput.dispatchEvent(new Event("input"));
  fileUploadWrapper.classList.remove("file-uploaded");

  // Create and display user message
  const messageContent = `<div class="message-text"></div>
                          ${userData.file.data ? `<img src="data:${userData.file.mime_type};base64,${userData.file.data}" class="attachment" />` : ""}`;

  const outgoingMessageDiv = createMessageElement(messageContent, "user-message");
  outgoingMessageDiv.querySelector(".message-text").innerText = userData.message;
  chatBody.appendChild(outgoingMessageDiv);
  chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });

  // Simulate bot response with thinking indicator after a delay
  setTimeout(() => {
    const messageContent = `<svg class="bot-avatar" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 1024 1024">
            <path
              d="M738.3 287.6H285.7c-59 0-106.8 47.8-106.8 106.8v303.1c0 59 47.8 106.8 106.8 106.8h81.5v111.1c0 .7.8 1.1 1.4.7l166.9-110.6 41.8-.8h117.4l43.6-.4c59 0 106.8-47.8 106.8-106.8V394.5c0-59-47.8-106.9-106.8-106.9zM351.7 448.2c0-29.5 23.9-53.5 53.5-53.5s53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5-53.5-23.9-53.5-53.5zm157.9 267.1c-67.8 0-123.8-47.5-132.3-109h264.6c-8.6 61.5-64.5 109-132.3 109zm110-213.7c-29.5 0-53.5-23.9-53.5-53.5s23.9-53.5 53.5-53.5 53.5 23.9 53.5 53.5-23.9 53.5-53.5 53.5zM867.2 644.5V453.1h26.5c19.4 0 35.1 15.7 35.1 35.1v121.1c0 19.4-15.7 35.1-35.1 35.1h-26.5zM95.2 609.4V488.2c0-19.4 15.7-35.1 35.1-35.1h26.5v191.3h-26.5c-19.4 0-35.1-15.7-35.1-35.1zM561.5 149.6c0 23.4-15.6 43.3-36.9 49.7v44.9h-30v-44.9c-21.4-6.5-36.9-26.3-36.9-49.7 0-28.6 23.3-51.9 51.9-51.9s51.9 23.3 51.9 51.9z"/></svg>
          <div class="message-text">
            <div class="thinking-indicator">
              <div class="dot"></div>
              <div class="dot"></div>
              <div class="dot"></div>
            </div>
          </div>`;

    const incomingMessageDiv = createMessageElement(messageContent, "bot-message", "thinking");
    chatBody.appendChild(incomingMessageDiv);
    chatBody.scrollTo({ top: chatBody.scrollHeight, behavior: "smooth" });
    generateBotResponse(incomingMessageDiv);
  }, 600);
};

// Adjust input field height dynamically
messageInput.addEventListener("input", () => {
  messageInput.style.height = `${initialInputHeight}px`;
  messageInput.style.height = `${messageInput.scrollHeight}px`;
  document.querySelector(".chat-form").style.borderRadius = messageInput.scrollHeight > initialInputHeight ? "15px" : "32px";
});

// Handle Enter key press for sending messages
messageInput.addEventListener("keydown", (e) => {
  const userMessage = e.target.value.trim();
  if (e.key === "Enter" && !e.shiftKey && userMessage && window.innerWidth > 768) {
    handleOutgoingMessage(e);
  }
});

// Handle file input change and preview the selected file
fileInput.addEventListener("change", () => {
  const file = fileInput.files[0];
  if (!file) return;

  const reader = new FileReader();
  reader.onload = (e) => {
    fileInput.value = "";
    fileUploadWrapper.querySelector("img").src = e.target.result;
    fileUploadWrapper.classList.add("file-uploaded");
    const base64String = e.target.result.split(",")[1];

    // Store file data in userData
    userData.file = {
      data: base64String,
      mime_type: file.type,
    };
  };

  reader.readAsDataURL(file);
});

// Cancel file upload
fileCancelButton.addEventListener("click", () => {
  userData.file = {};
  fileUploadWrapper.classList.remove("file-uploaded");
});

// Initialize emoji picker and handle emoji selection
const picker = new EmojiMart.Picker({
  theme: "light",
  skinTonePosition: "none",
  previewPosition: "none",
  onEmojiSelect: (emoji) => {
    const { selectionStart: start, selectionEnd: end } = messageInput;
    messageInput.setRangeText(emoji.native, start, end, "end");
    messageInput.focus();
  },
  onClickOutside: (e) => {
    if (e.target.id === "emoji-picker") {
      document.body.classList.toggle("show-emoji-picker");
    } else {
      document.body.classList.remove("show-emoji-picker");
    }
  },
});

document.querySelector(".chat-form").appendChild(picker);

sendMessage.addEventListener("click", (e) => handleOutgoingMessage(e));
document.querySelector("#file-upload").addEventListener("click", () => fileInput.click());
closeChatbot.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));
