<%@ page import="java.sql.*" %>
<%
  String username = request.getParameter("name");
  String username = request.getParameter("username");
  String email = request.getParameter("email");
  String password = request.getParameter("password");

  
  PreparedStatement stmt = null;

  try {
    Class.forName("oracle.jdbc.driver.OracleDriver");
    Connection con = DriverManager.getConnection("jdbc:oracle:thin:@localhost:1521:xe","system","thanos");

    String sql = "INSERT INTO users (name,username,email,password) VALUES (?,?,?,?)";
    stmt = con.prepareStatement(sql);
    stmt.setString(1, name);
    stmt.setString(2, username);
    stmt.setString(3, email);
    stmt.setString(4, password);
    stmt.executeUpdate();

    out.println("Signup successful!");

  } catch (Exception e) {
    out.println("Error: " + e.getMessage());
  } 
%>
