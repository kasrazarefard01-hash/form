from sqlite3 import connect
from cryptography.fernet import Fernet

connection = connect("my_file")
cor = connection.cursor()


with open("secret.txt" , "rb") as reader:
    key = reader.read()

f = Fernet(key)
cor.execute("CREATE TABLE IF NOT EXISTS users(service VARCHAR(50),email VARCHAR(100) , password VARCHAR(255))")


try:
    while True:
        print("1.show 2.add 3.edit 4.remove 5.search 6.exit")
        print()
        try:
            entry = int(input("please enter your number of work: "))
        except ValueError:
            print("please enter number")
            continue
        
        # show #
        if entry == 1:
            cor.execute("SELECT * FROM users")
            info = cor.fetchall()
            for i in info:
                service,email,password = i
                password = f.decrypt(password.encode()).decode()
                print(f"service: {service}\nemail:{email}\npassword:{password}\n")
            
        # add #
        elif entry == 2:
            entry_service = str(input("please enter the service: "))
            
            cor.execute("SELECT * FROM users WHERE service = ?" , (entry_service,))
            
            if cor.fetchall() != []:
                print(f"{entry_service} already exists")
                
            else:
                entry_email = str(input("please enter your email: "))
                entry_password = str(input("please enter your password: "))
                
                entry_password = f.encrypt(entry_password.encode()).decode()
                cor.execute("INSERT INTO users(service,email,password) VALUES(?,?,?)" , (entry_service,entry_email,entry_password))        
                connection.commit()
                print("new data added")
        # edit #
        elif entry == 3:
            entry_service = str(input("enter the service that you want to change: "))
            cor.execute("SELECT * FROM users WHERE service = ?" , (entry_service,))
            info_c = cor.fetchall()
            
            if info_c != []:
                
                try:
                    entry_change = int(input("what item do you want to change 1.service name 2.email 3.password : "))
                    
                except ValueError:
                    print("please enter number")
                    continue
                    
                if entry_change == 1:
                    entry_new = str(input("enter the new service: "))
                    cor.execute("SELECT * FROM users WHERE service = ?" , (entry_new,))
                    
                    if cor.fetchall() != []:
                        print(f"{entry_new} already exists")
                    else:
                
                        cor.execute("UPDATE users SET service =? WHERE service =?" ,(entry_new,entry_service))
                        print("Changes were recorded")
                        connection.commit()
         
                elif entry_change == 2:
                    entry_new = str(input("enter the new email: "))
                    cor.execute("UPDATE users SET email =? WHERE service =?" ,(entry_new,entry_service))
                    print("Changes were recorded")
                    connection.commit()
               
                elif entry_change == 3:
                    entry_new = str(input("enter the new password: "))
                    entry_new = f.encrypt(entry_new.encode()).decode()
                    cor.execute("UPDATE users SET password =? WHERE service =?" ,(entry_new,entry_service))
                    print("Changes were recorded")
                    connection.commit()
            else:
                print(f"{entry_service} wasn't found")
                
                
        elif entry == 4:
            entry_del = str(input("enter the service that you want to delete: "))
            cor.execute("SELECT * FROM users WHERE service = ?" , (entry_del,))
            info_del = cor.fetchall()
            
            if info_del != []:
                cor.execute("DELETE FROM users WHERE service = ?" , (entry_del,))
                connection.commit()
                print("deleted")
            else:
                print(f"{entry_del} wasn't found")
                
        
        elif entry == 5:
            entry_search = str(input("enter the service to search: "))
            cor.execute("SELECT * FROM users WHERE service = ?" , (entry_search,))
            info_s = cor.fetchall()
            
            if info_s != []:
                for x in info_s:
                    service,email,password = x
                    password = f.decrypt(password.encode()).decode()
                    print(f"\nservice: {service}\nemail:{email}\npassword:{password}\n")
            else:
                print(f"{entry_search} wasn't found")
                
        elif entry == 6:
            break
        
        
        else:
            print("invalid choice")
            
    print("thank you for using our program")

finally:
    connection.close()