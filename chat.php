<?php include('server.php') ?><?php include('server.php') ?>
<html>
<head>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700,800,900|Ubuntu" rel="stylesheet">
  <link href="css/chat.css" rel="stylesheet" >

  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <style>
.chat-container {
    width: 80%;
    max-width: 1000px;
    margin: auto;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    height: 600px; /* Increased height */
}

.chat-log {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    border: 1px solid #ccc;
    border-radius: 8px;
    background-color: #fff;
    height: 800px; /* Increased height */
}


  .input-group {
      display: flex;
      padding: 10px;
  }

  .input-group input {
      flex: 1;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
  }

  .input-group button {
      background: #007bff;
      color: white;
      border: none;
      padding: 10px;
      border-radius: 5px;
      margin-left: 5px;
      cursor: pointer;
  }

  .user-message {
      text-align: right;
      background: #007bff;
      color: white;
      padding: 8px;
      border-radius: 8px;
      margin: 5px;
      width: fit-content;
      align-self: flex-end;
  }

  .chatbot-message {
      text-align: left;
      background: #e9ecef;
      color: black;
      padding: 8px;
      border-radius: 8px;
      margin: 5px;
      width: fit-content;
      align-self: flex-start;
  }
</style>

</head>

<body>

  <div class="cont">
    <?php if(isset($_SESSION['full_name'])): ?>
      <button class="btn btn-light btn-lg" type="button" name="button"><a href="index.php?logout='1'" style="color:black;text-decoration: none;padding:1rem;">Logout</a></button>
      <button id="formButton" class="btn btn-light btn-lg" type="button" name="button"><a href="chat.php#form1" style="color:black;text-decoration: none;">Update Info</a></button>
      <h1>Welcome <?php echo (string)$_SESSION['full_name'];?></h1>
    <?php endif ?>
  </div>

  <!-- <div class="formDiv">
    <form id="form1" name="form1" action="chat.php" method="post" style="display:none;">
      <?php include('errors.php'); ?>
      <div class="form-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-user"></i> </span>
        </div>
        <input class="form-control" placeholder="Full name" type="text" name="name" value="<?php echo (string)$_SESSION['full_name'];?>">
      </div>
      <div class="form-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-envelope"></i> </span>
        </div>
        <input class="form-control" placeholder="Email address" type="text" name="email1" value=<?php echo (string)$_SESSION['email_id']; ?> readonly>
      </div>
      <div class="form-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
        </div>
        <input class="form-control" placeholder="Mobile Number" type="text" name="phone" value=<?php echo (string)$_SESSION['mobile_no']; ?>>
      </div>
      <div class="form-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
        </div>
        <input class="form-control" placeholder="Create password" type="password" name="pwd1" value=<?php echo (string)$_SESSION['password'] ?>>
      </div>
      <div class="form-group input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
        </div>
        <input class="form-control" placeholder="Repeat password" type="password" name="pwd2" value=<?php echo (string)$_SESSION['password'] ?>>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-light btn-block" name="update"> Update </button>
      </div>
    </form>
  </div> -->


  <div class="formDiv" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
    <form id="form1" name="form1" action="chat.php" method="post" style="display: none; width: 100%; max-width: 400px;">
      <?php include('errors.php'); ?>
      
      <div class="form-group input-group" style="margin-bottom: 15px;">
        <div class="input-group-prepend">
          <span class="input-group-text" style="width: 40px; justify-content: center;"> 
            <i class="fa fa-user"></i> 
          </span>
        </div>
        <input class="form-control" placeholder="Full name" type="text" name="name" 
               value="<?php echo (string)$_SESSION['full_name'];?>">
      </div>

      <div class="form-group input-group" style="margin-bottom: 15px;">
        <div class="input-group-prepend">
          <span class="input-group-text" style="width: 40px; justify-content: center;"> 
            <i class="fa fa-envelope"></i> 
          </span>
        </div>
        <input class="form-control" placeholder="Email address" type="text" name="email1" 
               value="<?php echo (string)$_SESSION['email_id']; ?>" readonly>
      </div>

      <div class="form-group input-group" style="margin-bottom: 15px;">
        <div class="input-group-prepend">
          <span class="input-group-text" style="width: 40px; justify-content: center;"> 
            <i class="fa fa-phone"></i> 
          </span>
        </div>
        <input class="form-control" placeholder="Mobile Number" type="text" name="phone" 
               value="<?php echo (string)$_SESSION['mobile_no']; ?>">
      </div>

      <div class="form-group input-group" style="margin-bottom: 15px;">
        <div class="input-group-prepend">
          <span class="input-group-text" style="width: 40px; justify-content: center;"> 
            <i class="fa fa-lock"></i> 
          </span>
        </div>
        <input class="form-control" placeholder="Create password" type="password" name="pwd1" 
               value="<?php echo (string)$_SESSION['password']; ?>">
      </div>

      <div class="form-group input-group" style="margin-bottom: 15px;">
        <div class="input-group-prepend">
          <span class="input-group-text" style="width: 40px; justify-content: center;"> 
            <i class="fa fa-lock"></i> 
          </span>
        </div>
        <input class="form-control" placeholder="Repeat password" type="password" name="pwd2" 
               value="<?php echo (string)$_SESSION['password']; ?>">
      </div>

      <div class="form-group" style="text-align: center; width: 110%;">
        <button type="submit" class="btn btn-light" name="update" 
                style="width: 100%; padding: 12px; font-size: 16px; text-align: center; border-radius: 5px;"> 
          Update 
        </button>
      </div>
    </form>
</div>


  <!-- Chat UI -->
  <div class="chat-container">
      <div id="chat-log" class="chat-log"></div>
      <div class="input-group">
          <input id="user-input" type="text" class="form-control" placeholder="Type your message here...">
          <div class="input-group-append">
              <button id="send-button" class="btn btn-primary">Send</button>
          </div>
      </div>
      <div class="button-group">
          <button id="report-button" class="btn btn-secondary" style="display:none;">Get Report</button>
          <button id="diagnosis-button" class="btn btn-secondary" style="display:none;">Diagnosis</button>
      </div>
  </div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
      function appendInitialMessage() {
          appendMessage("Chatbot", "Hi there! How are you feeling today?");
      }
      function appendMessage(sender, message) {
          let chatLog = document.getElementById("chat-log");
          let div = document.createElement("div");
          div.className = sender.toLowerCase() + "-message";
          div.innerHTML = `<strong>${sender}:</strong> ${message}`;
          chatLog.appendChild(div);
          chatLog.scrollTop = chatLog.scrollHeight;
      }

      async function sendMessage() {
          let userInput = document.getElementById("user-input");
          let message = userInput.value.trim();
          if (!message) return;

          appendMessage("User", message);
          userInput.value = "";

          try {
              let response = await fetch("server.php", {
                  method: "POST",
                  headers: { "Content-Type": "application/x-www-form-urlencoded" },
                  body: "user_input=" + encodeURIComponent(message),
              });
              let data = await response.json();
              appendMessage("Chatbot", data.response || "Sorry, I couldn't understand that.");
          } catch (error) {
              appendMessage("Chatbot", "Error connecting to the server.");
          }
      }

      document.getElementById("send-button").addEventListener("click", sendMessage);
      document.getElementById("user-input").addEventListener("keypress", function (e) {
          if (e.key === "Enter") {
              sendMessage();
          }
      });
      appendInitialMessage();
  });

  document.addEventListener("DOMContentLoaded", function() {
      const formButton = document.getElementById("formButton");
      const form1 = document.getElementById("form1");
      
     
      formButton.addEventListener("click", function(e) {
        e.preventDefault();
        if (form1.style.display === "none" || !form1.style.display) {
          form1.style.display = "block";
        } else {
          form1.style.display = "none";
        }
      });

   

        form1.addEventListener("submit", function(e) {
        e.preventDefault();
        
        
        const formData = new URLSearchParams(new FormData(form1));
        formData.append('update', '1');
        
        fetch("server.php", {
            method: "POST",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData.toString()
        })
        .then(response => response.text())
        .then(text => {
            console.log("Server response:", text);
            
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    alert("Information updated successfully!");
                    form1.style.display = "none";
                    const welcomeHeader = document.querySelector('h1');
                    if (welcomeHeader) {
                        welcomeHeader.textContent = 'Welcome ' + formData.get('name');
                    }
                } else {
                    alert(data.error || "Update failed");
                }
            } catch (e) {
                console.error("Parse error:", e);
                console.error("Response text:", text);
                alert("Error processing server response");
            }
        })
        .catch(error => {
            console.error("Network error:", error);
            alert("Error connecting to server");
        });
    });
  });
</script>

  <script src="scripts/chat.js"></script>
</body>
</html>