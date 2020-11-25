/*
 * Carter Mooring
 * HW8
 * CPSC321 Databases
 * Nov. 21st, 2020
 * Description: This program connects to a database previously made on ada.
 * 				It acts as a simple test based interface for the DB that recieves input
 * 				from a user and can perform actions on the DB such as deletes.
 */

//To run: javac hw8.java
//        java hw8

import java.io.FileInputStream;
import java.io.IOException;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.PreparedStatement;
import java.util.Properties;
import java.util.Scanner;

public class hw8 {
    public static void main(String[] args) {
	try {
	    // Info for Connection
	    Properties prop = new Properties();
	    FileInputStream in = new FileInputStream("config.properties");
	    prop.load(in);
		in.close();
		
	    // Database Connection
	    String hst = prop.getProperty("host");
	    String usr = prop.getProperty("user");
	    String pwd = prop.getProperty("password");
	    String dab = "cmooring_DB";
	    String url = "jdbc:mysql://" + hst + "/" + dab;
	    Connection con = DriverManager.getConnection(url, usr, pwd);
	    
	    // Query are Created & Executed
	    String q;
	    ResultSet rs;
	    int userInput;
	    Scanner reader = new Scanner(System.in);
	    do {
			System.out.println("1. List countries\n2. Add country\n3. Find countries based on gdp and inflation\n4. Update country's gdp and inflation\n5. Exit");
			System.out.print("Enter your choice (1-5):");
			userInput = Integer.parseInt(reader.next());
			if(userInput == 1) {
				q = "SELECT country_name, country_code FROM country";
				PreparedStatement pstmt = con.prepareStatement(q);
				rs = pstmt.executeQuery(q);
				// print results
				while(rs.next()) {
				String catid = rs.getString("country_name");
							String name = rs.getString("country_code");
							System.out.println(catid + ", " + name);
						}
				pstmt.close();
				rs.close();
			} else if (userInput == 2) {
				System.out.print("Country code................:");
				String countryCode = reader.next();
				System.out.print("Country name................:");
				String countryName = reader.next();
				System.out.print("Country per capida gdp (USD):");
				double gdp = Double.parseDouble(reader.next());
				System.out.print("Country inflation (pct).....:");
				double inflation = Double.parseDouble(reader.next());
				q = "INSERT INTO country VALUES(?,?,?,?)";
				PreparedStatement pstmt = con.prepareStatement(q);
				pstmt = con.prepareStatement(q);
				pstmt.setString(1,countryCode);
				pstmt.setString(2,countryName);
				pstmt.setDouble(3,gdp);
				pstmt.setDouble(4,inflation);
				pstmt.execute();
				pstmt.close();
			} else if (userInput == 3) {
				System.out.print("Number of countries to display: ");
				int numCountries = Integer.parseInt(reader.next());
				System.out.print("Minimum per capita gdp (USD)..: ");
				double gdp = Double.parseDouble(reader.next());
				System.out.print("Maximum inflation (pct).......: ");
				double inflation = Double.parseDouble(reader.next());
				q = "SELECT * FROM country WHERE gdp>=? AND inflation<=? ORDER BY gdp DESC, inflation LIMIT ?";
				PreparedStatement pstmt = con.prepareStatement(q);
				pstmt.setDouble(1,gdp);
				pstmt.setDouble(2,inflation);
				pstmt.setInt(3,numCountries);
				rs = pstmt.executeQuery();
				while(rs.next()) {
				String name = rs.getString("country_name");
							String cc = rs.getString("country_code");   
							double g = rs.getDouble("gdp");
							double i = rs.getDouble("inflation");
							System.out.println(name + " (" + cc + "), " + g + ", " + i);
						}
				rs.close();
				pstmt.close();
			} else if(userInput == 4) {
				System.out.print("Country code: ");
				String cc = reader.next();
				System.out.print("Country per capita gdp (USD)..: ");
				double g = Double.parseDouble(reader.next());
				System.out.print("Country inflation (pct).......: ");
				double i = Double.parseDouble(reader.next());
				q = "UPDATE country SET gdp=?, inflation=? WHERE country_code=?";
				PreparedStatement pstmt = con.prepareStatement(q);
				pstmt.setDouble(1,g);
				pstmt.setDouble(2,i);
				pstmt.setString(3,cc);
				pstmt.execute();
			}
	    } while(userInput != 5);
	    // Resources released
	    con.close();
	} catch(Exception err) {
	    err.printStackTrace();
	}
    }
}   
