# Struktur data menggunakan list untuk menyimpan kontak
contacts = []

# Fungsi untuk menambah kontak baru
def add_contact():
    name = input("Enter name: ")
    phone = input("Enter phone number: ")
    email = input("Enter email: ")
    contact = {'name': name, 'phone': phone, 'email': email}
    contacts.append(contact)
    print("Contact added successfully!")

# Fungsi untuk menampilkan semua kontak
def list_contacts():
    if len(contacts) == 0:
        print("No contacts found.")
    else:
        for i, contact in enumerate(contacts, start=1):
            print(f"{i}. {contact['name']} - {contact['phone']} - {contact['email']}")

# Fungsi untuk mengedit kontak
def edit_contact():
    list_contacts()
    try:
        index = int(input("Enter the number of the contact to edit: ")) - 1
        if 0 <= index < len(contacts):
            print(f"Editing contact: {contacts[index]['name']}")
            contacts[index]['name'] = input("Enter new name: ")
            contacts[index]['phone'] = input("Enter new phone number: ")
            contacts[index]['email'] = input("Enter new email: ")
            print("Contact updated successfully!")
        else:
            print("Invalid contact number.")
    except ValueError:
        print("Invalid input. Please enter a number.")

# Fungsi untuk menghapus kontak
def delete_contact():
    list_contacts()
    try:
        index = int(input("Enter the number of the contact to delete: ")) - 1
        if 0 <= index < len(contacts):
            contacts.pop(index)
            print("Contact deleted successfully!")
        else:
            print("Invalid contact number.")
    except ValueError:
        print("Invalid input. Please enter a number.")

# Fungsi untuk menampilkan menu
def show_menu():
    print("\nContact Management System")
    print("1. Add contact")
    print("2. List contacts")
    print("3. Edit contact")
    print("4. Delete contact")
    print("5. Exit")
    return input("Choose an option: ")

# Fungsi utama untuk menjalankan aplikasi
def main():
    while True:
        choice = show_menu()

        if choice == '1':
            add_contact()
        elif choice == '2':
            list_contacts()
        elif choice == '3':
            edit_contact()
        elif choice == '4':
            delete_contact()
        elif choice == '5':
            print("Exiting program...")
            break
        else:
            print("Invalid option, please try again.")

if __name__ == "__main__":
    main()
