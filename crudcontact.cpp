#include <iostream>
#include <vector>
#include <string>

using namespace std;

// Struktur untuk menyimpan informasi kontak
struct Contact {
    string name;
    string phone;
    string email;
};

// Vektor untuk menyimpan daftar kontak
vector<Contact> contacts;

// Fungsi untuk menambah kontak baru
void addContact() {
    Contact newContact;
    cout << "Enter name: ";
    cin.ignore(); // Mengabaikan newline sebelumnya
    getline(cin, newContact.name);
    cout << "Enter phone number: ";
    getline(cin, newContact.phone);
    cout << "Enter email: ";
    getline(cin, newContact.email);
    contacts.push_back(newContact);
    cout << "Contact added successfully!\n";
}

// Fungsi untuk menampilkan semua kontak
void listContacts() {
    if (contacts.empty()) {
        cout << "No contacts found.\n";
    } else {
        for (int i = 0; i < contacts.size(); ++i) {
            cout << i + 1 << ". " << contacts[i].name << " - " 
                << contacts[i].phone << " - " << contacts[i].email << endl;
        }
    }
}

// Fungsi untuk mengedit kontak
void editContact() {
    int index;
    cout << "Enter the number of the contact to edit: ";
    cin >> index;
    if (index > 0 && index <= contacts.size()) {
        cout << "Editing contact: " << contacts[index - 1].name << endl;
        cout << "Enter new name: ";
        cin.ignore();
        getline(cin, contacts[index - 1].name);
        cout << "Enter new phone number: ";
        getline(cin, contacts[index - 1].phone);
        cout << "Enter new email: ";
        getline(cin, contacts[index - 1].email);
        cout << "Contact updated successfully!\n";
    } else {
        cout << "Invalid contact number.\n";
    }
}

// Fungsi untuk menghapus kontak
void deleteContact() {
    int index;
    cout << "Enter the number of the contact to delete: ";
    cin >> index;
    if (index > 0 && index <= contacts.size()) {
        contacts.erase(contacts.begin() + index - 1);
        cout << "Contact deleted successfully!\n";
    } else {
        cout << "Invalid contact number.\n";
    }
}

// Fungsi untuk menampilkan menu
void showMenu() {
    cout << "\nContact Management System\n";
    cout << "1. Add contact\n";
    cout << "2. List contacts\n";
    cout << "3. Edit contact\n";
    cout << "4. Delete contact\n";
    cout << "5. Exit\n";
    cout << "Choose an option: ";
}

// Fungsi utama untuk menjalankan aplikasi
int main() {
    int choice;
    do {
        showMenu();
        cin >> choice;

        switch (choice) {
            case 1:
                addContact();
                break;
            case 2:
                listContacts();
                break;
            case 3:
                editContact();
                break;
            case 4:
                deleteContact();
                break;
            case 5:
                cout << "Exiting program...\n";
                break;
            default:
                cout << "Invalid option, please try again.\n";
        }
    } while (choice != 5);

    return 0;
}
