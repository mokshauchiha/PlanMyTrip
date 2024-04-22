<%@ page import="java.util.*" %>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=League+Spartan:wght@200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>Itenerary Planner</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="stylei.css">
</head>
<body>
    <div class="Body">
        <div class="leftpanel">
            <div class="logo">
                <img src="images/LogoBlue.png" alt="Logo">
            <h3>TRAVELLING MADE EASY</h3>
            </div>
            <div class="contents">
                <ul>
                    <a href="index.html">
                            <li>
                            <img src="images/Dashboard.svg" alt="Dashboad">
                            Dashboard 
                        </li>
                    </a>
                    <a href="blogpost.html">
                        <li>
                            <img src="images/Heart.svg" alt="Dashboad">
                            Blogs & Post 
                        </li>
                    </a>
                    <a href="iteneraryPlanner.jsp">
                        <li>
                            <img src="images/Pencil.svg" alt="Pencil">
                            Itenerary<br>Planner
                        </li> 
                    </a>
                    <a href="packing-list.html">
                            <li>
                            <img src="images/Bag.svg" alt="Bag">
                            Packing List 
                        </li>
                    </a>
                    <a href="profile.html">
                            <li>
                            <img src="images/account.png" alt="profile">
                            Profile
                        </li>
                    </a>
                    
                </ul>
            </div>
        </div>
        <div class="centerpanel" style="max-height: none;">
            <div class="centerhead">
                <div class="greeting">
                    <h2>Hello, Mokshagna!</h2>
                <p>Itenerary Planner</p>
                </div>
    
                <div class="search">
                    <img src="images/Search.svg" alt="Search">
                    <input type="text" placeholder="Search">
                </div>
    
                <div class="notifi">
                    <img src="images/Notifi.svg" alt="Notification">
                </div>
            </div>
            <div class="todolist">
                <h1>Itenerary Planner For Schedule : schd1</h1>
                <div id="itinerary">
                    <div class="itedet">
                        <h2>Your Itinerary</h2>
                        <ul>
                          <li>Destination: <span id="itinerary-destination"></span></li>
                          <li>Arrival Date: <span id="itinerary-arrival-date"></span></li>
                          <li>Departure Date: <span id="itinerary-departure-date"></span></li>
                        </ul>
                    </div>
                  </div>
                <div class="addform">
                      <form method="post">
                      <input type="text" name="todo" placeholder="Enter your Plan" required>
                      <input type="submit" value="Add">
                      </form>
                  </div>
                <div class="plannings">
                      <h2 id="MP">My Plannings are :</h2>
                      <ul>
                      <%
                          List<String> todoList = (List<String>) session.getAttribute("todoList");
                          if (todoList == null) {
                              todoList = new ArrayList<>();
                              session.setAttribute("todoList", todoList);
                          }
                          if (request.getMethod().equals("POST")) {
                              // If the form was submitted, add the new to-do item to the list
                              String newTodo = request.getParameter("todo");
                              if (request.getParameter("delete") != null) {
                                  // If the delete button was clicked, remove the specified item from the list
                                  int index = Integer.parseInt(request.getParameter("delete"));
                                  todoList.remove(index);
                              } else {
                                  if (newTodo == null) {
                                      newTodo = "";
                                  }
                                  if (!newTodo.isEmpty()) {
                                      todoList.add(newTodo);
                                  }
                              }
                              session.setAttribute("todoList", todoList);
                          }
                          // Display the to-do list items
                          for (int i = 0; i < todoList.size(); i++) {
                              String todoItem = todoList.get(i);
                              %>
                              <li><%= todoItem %></li>
                              <form method="post">
                                  <input type="hidden" name="delete" value="<%= i %>">
                                  <button>Delete</button>
                              </form>
                              <%
                          }
                      %>
                      <button type="submit" id="Save">SAVE</button>
                    </ul>
                </div>
                <div class="ticket-upload">
                    <h4>Upload TICKETS (Hotels or Flights) :</h4>
                    <label for="files">Select files:</label>
                    <input type="file" id="files" name="files" multiple><br><br>
                    <input type="submit" value="Upload">
                    <br>
                    <br>
                    <p>you can book from most famous or popular websites for Booking Hotel or Flight</p><br>
                    <p>Here are some :</p><br>
                    <a href="https://tickets.paytm.com/flights/">
                        <img src="images/Paytm-Logo.wine.svg" alt="paytm">
                        Paytm
                    </a>
                    <a href="https://www.trivago.in/">
                        <img src="images/Trivago-Logo.wine.svg" alt="paytm">
                        Trivago
                    </a>
                    <a href="https://www.airindia.com/in/en/book/search-flights.html">
                        <img src="images/Air_India-Logo.wine.svg" alt="paytm">
                        Air India
                    </a>
                    <a href="https://www.oyorooms.com/">
                        <img src="images/Oyo_Rooms-Logo.wine.svg" alt="paytm">
                        Oyo
                    </a>
                    <a href="https://in.hotels.com/">
                        <img src="images/Hotels.com-Logo.wine.svg" alt="paytm">
                        Hotels
                    </a>
                    <a href="https://www.tripadvisor.com/">
                        <img src="images/TripAdvisor-Logo.wine.svg" alt="paytm">
                        Trip Advisor
                    </a>
                    <a href="https://www.emirates.com/">
                        <img src="images/Emirates_(airline)-Logo.wine.svg" alt="paytm">
                        Emirates
                    </a>
                </div>
            </div>
    </div>
    </div>
    <img class="bimg1" src="images/Ellipse.png" alt="ellipse">
    <div class="foot">
        <img src="images/LogoWhite.png" alt="logo">
            <div class="footabout">
                <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Eveniet at facilis itaque ratione quia provident nisi unde eum sunt dolore, qui labore ex nobis molestiae, ipsa iure odit enim repellat nemo quod quis nesciunt. Perferendis obcaecati atque officiis eaque omnis provident quas dolores magni similique sequi doloremque fugiat nobis, exercitationem, consectetur ullam assumenda quod illum magnam sapiente, asperiores non sed.</p>
                <p>+91 000000000</p>
                <p>dummy@gmail.com</p>

                <img src="images/facebook.png" alt="facebook">
                <img src="images/instagram.png" alt="instagram">
                <img src="images/youtube.png" alt="youtube">
                <img src="images/twitter.png" alt="twitter">
            </div>
    </div>
</body>
</html>