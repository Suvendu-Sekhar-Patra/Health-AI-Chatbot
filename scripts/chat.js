// document.addEventListener("DOMContentLoaded", function () {
//     const chatLog = document.getElementById("chat-log");
//     const userInput = document.getElementById("user-input");
//     const sendButton = document.getElementById("send-button");
//     const reportButton = document.getElementById("report-button");
//     const diagnosisButton = document.getElementById("diagnosis-button");
//     const formButton = document.getElementById("formButton");
//     const form1 = document.getElementById("form1");

//     let userResponses = [];
//     let questionCount = 0;
//     const minQuestions = 7;
//     // const maxQuestions = 15;
//     const maxQuestions = 7;

//     const questions = [
//     "What symptoms are you currently experiencing? (Please list all)",
//     "How long have you been experiencing these symptoms? (In days)",
//     "On a scale of 1 to 10, how severe are your symptoms? (1 being mild, 10 being severe)",
//     "Do you have any pre-existing medical conditions? (e.g., diabetes, hypertension)",
//     "Are you currently taking any medications? If yes, please list them.",
//     "How would you describe your diet? (e.g., balanced, high in sugar, vegetarian)",
//     "How often do you exercise? (e.g., daily, weekly, rarely)",
//     ];

//     function appendMessage(sender, message) {
//         const messageDiv = document.createElement("div");
//         messageDiv.innerHTML = `<strong>${sender}:</strong> ${message}`;
//         chatLog.appendChild(messageDiv);
//         chatLog.scrollTop = chatLog.scrollHeight;
//     }

//     function askQuestion() {
//         if (questionCount < maxQuestions) {
//             appendMessage("Medical Bot", questions[questionCount]);
//             questionCount++;
//         }
//         if (questionCount >= minQuestions) {
//             reportButton.style.display = "block";
//             diagnosisButton.style.display = "block";
//         }
//     }

//     sendButton.addEventListener("click", function () {
//         const userText = userInput.value.trim();
//         if (userText !== "") {
//             appendMessage("You", userText);
//             userResponses.push(userText);
//             userInput.value = "";
//             setTimeout(askQuestion, 1000);
//         }
//     });

//     reportButton.addEventListener("click", function () {
//         let report = "Health Report Summary:\n";
//         userResponses.forEach((resp, index) => {
//             report += `Q${index + 1}: ${resp}\n`;
//         });
//         alert(report);
//     });

//     diagnosisButton.addEventListener("click", function () {
//         alert("Redirecting to further diagnosis...");
//         window.location.href = "diagnosis.html";
//     });

//     formButton.addEventListener("click", function () {
//         form1.style.display = form1.style.display === "none" ? "block" : "none";
//     });

//     appendMessage("Medical Bot", "Hello! Let's check your symptoms.");
//     setTimeout(askQuestion, 1000);
// });



// document.addEventListener("DOMContentLoaded", function () {
//     const chatLog = document.getElementById("chat-log");
//     const userInput = document.getElementById("user-input");
//     const sendButton = document.getElementById("send-button");
//     const reportButton = document.getElementById("report-button");
//     const diagnosisButton = document.getElementById("diagnosis-button");

//     let userResponses = {};
//     let currentStep = 0;
//     let symptomDetected = "";
//     let awaitingSymptomDetails = false;

//     const symptomFollowUps = {
//         "vomiting": "🤢 How many times have you vomited in the last 24 hours?",
//         "fever": "🌡️ You mentioned fever. Can you tell me your body temperature in °F or °C?",
//         "cough": "🤧 Is your cough dry or with phlegm?",
//         "headache": "💥 Where exactly is your headache located? (forehead, sides, back of the head?)",
//         "body aches": "🦴 Do you feel muscle weakness or joint pain along with body aches?",
//         "nausea": "🤢 Have you lost your appetite as well?",
//         "sore throat": "🔥 Does it hurt more when swallowing?"
//     };

//     const generalQuestions = [
//         "⏳ How long have you had these symptoms? (e.g., 2 days, 1 week)",
//         "📊 On a scale of 1 to 10, how severe are your symptoms?",
//         "🏥 Do you have any chronic conditions like diabetes, hypertension, or asthma?",
//         "💊 Are you currently taking any medication? If so, please list them.",
//         "🍏 What does your diet mainly consist of? (e.g., balanced, high in sugar, vegetarian)",
//         "🏃 How often do you exercise? (e.g., daily, weekly, rarely)",
//         "✅ Thank you! Based on your responses, I will now suggest home remedies and over-the-counter treatments."
//     ];

//     function appendMessage(sender, message) {
//         const messageDiv = document.createElement("div");
//         messageDiv.innerHTML = `<strong>${sender}:</strong> ${message}`;
//         chatLog.appendChild(messageDiv);
//         chatLog.scrollTop = chatLog.scrollHeight;
//     }

//     async function sendToGemini(userText) {
//         try {
//             const response = await fetch("server.php", {
//                 method: "POST",
//                 headers: { "Content-Type": "application/x-www-form-urlencoded" },
//                 body: new URLSearchParams({ user_input: userText })
//             });

//             const data = await response.json();
//             return data.response || "🤖 Sorry, I didn't understand that. Please try again.";
//         } catch (error) {
//             return "❌ Failed to connect to the server. Please check your internet connection.";
//         }
//     }

//     function analyzeSymptoms(userText) {
//         const seriousConditions = ["cancer", "tumor", "malignancy"];

//         for (const condition of seriousConditions) {
//             if (userText.toLowerCase().includes(condition)) {
//                 symptomDetected = condition;
//                 return "💙 I'm really sorry to hear that. Are you currently undergoing any treatment like chemotherapy, radiation, or medication?";
//             }
//         }

//         for (const symptom in symptomFollowUps) {
//             if (userText.toLowerCase().includes(symptom)) {
//                 symptomDetected = symptom;
//                 awaitingSymptomDetails = true;
//                 return symptomFollowUps[symptom];
//             }
//         }
//         return null;
//     }

//     function askNextQuestion() {
//         if (currentStep < generalQuestions.length) {
//             setTimeout(() => appendMessage("Medical Bot", generalQuestions[currentStep]), 1000);
//         } else {
//             setTimeout(() => appendMessage("Medical Bot", "Would you like to receive a summary report?"), 2000);
//             reportButton.style.display = "block";
//             diagnosisButton.style.display = "block";
//         }
//     }

//     sendButton.addEventListener("click", async function () {
//         const userText = userInput.value.trim();
//         if (!userText) return;

//         appendMessage("You", userText);
//         userInput.value = "";

//         if (awaitingSymptomDetails) {
//             userResponses[`symptom_${symptomDetected}`] = userText;
//             appendMessage("Medical Bot", `Do you have any other symptoms apart from ${symptomDetected}?`);
//             awaitingSymptomDetails = false;
//             return;
//         }

//         if (currentStep === 0) {
//             let followUp = analyzeSymptoms(userText);
//             if (followUp) {
//                 appendMessage("Medical Bot", followUp);
//                 return;
//             }
//         } else {
//             userResponses[`question_${currentStep}`] = userText; // Store user response
//             currentStep++; // Move to the next question
//         }

//         askNextQuestion(); // Ask the next question
//     });

//     reportButton.addEventListener("click", function () {
//         let report = "🩺 **Health Report Summary:**\n\n";
//         Object.keys(userResponses).forEach((key, index) => {
//             report += `**Q${index + 1}:** ${userResponses[key]}\n`;
//         });
//         alert(report);
//     });

//     diagnosisButton.addEventListener("click", function () {
//         alert("Redirecting to further diagnosis...");
//         window.location.href = "diagnosis.html";
//     });

//     setTimeout(() => appendMessage("Medical Bot", "👋 Hello! I am your healthcare assistant. How can I help you today? Please describe your symptoms."), 1000);
// });






// document.addEventListener("DOMContentLoaded", function () {
//     const chatLog = document.getElementById("chat-log");
//     const userInput = document.getElementById("user-input");
//     const sendButton = document.getElementById("send-button");
//     const reportButton = document.getElementById("report-button");
//     const diagnosisButton = document.getElementById("diagnosis-button");

//     let userResponses = {};
//     let currentStep = 0;
//     let symptomDetected = "";
//     let awaitingSymptomDetails = false;

//     function appendMessage(sender, message) {
//         const messageDiv = document.createElement("div");
//         messageDiv.innerHTML = `<strong>${sender}:</strong> ${message}`;
//         chatLog.appendChild(messageDiv);
//         chatLog.scrollTop = chatLog.scrollHeight;
//     }

//     async function sendToGemini(userText) {
//         try {
//             const response = await fetch("server.php", {
//                 method: "POST",
//                 headers: { "Content-Type": "application/x-www-form-urlencoded" },
//                 body: new URLSearchParams({ user_input: userText })
//             });

//             const data = await response.json();
//             return data.response || "🤖 Sorry, I didn't understand that. Please try again.";
//         } catch (error) {
//             return "❌ Failed to connect to the server. Please check your internet connection.";
//         }
//     }



//     sendButton.addEventListener("click", async function () {
//         const userText = userInput.value.trim();
//         if (!userText) return;

//         appendMessage("You", userText);
//         userInput.value = "";

//         if (awaitingSymptomDetails) {
//             userResponses[`symptom_${symptomDetected}`] = userText;
//             appendMessage("Medical Bot", `Do you have any other symptoms apart from ${symptomDetected}?`);
//             awaitingSymptomDetails = false;
//             return;
//         }

//         if (currentStep === 0) {
//             let followUp = analyzeSymptoms(userText);
//             if (followUp) {
//                 appendMessage("Medical Bot", followUp);
//                 return;
//             }
//         } else {
//             userResponses[`question_${currentStep}`] = userText;
//             currentStep++; 
//         }

//         askNextQuestion(); // Ask the next question
//     });

//     reportButton.addEventListener("click", function () {
//         let report = "🩺 **Health Report Summary:**\n\n";
//         Object.keys(userResponses).forEach((key, index) => {
//             report += `**Q${index + 1}:** ${userResponses[key]}\n`;
//         });
//         alert(report);
//     });

//     diagnosisButton.addEventListener("click", function () {
//         alert("Redirecting to further diagnosis...");
//         window.location.href = "diagnosis.html";
//     });

//     setTimeout(() => appendMessage("Medical Bot", "👋 Hello! I am your healthcare assistant. How can I help you today? Please describe your symptoms."), 1000);
// });



