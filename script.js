// Simulando base de datos local con localStorage
const booksKey = 'books';

// Funciones CRUD
const loadBooks = () => {
    const books = JSON.parse(localStorage.getItem(booksKey)) || [];
    const tableBody = document.querySelector('#book-table tbody');
    tableBody.innerHTML = ''; // Limpiar tabla

    books.forEach((book, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${book.title}</td>
            <td>${book.author}</td>
            <td>${book.year}</td>
            <td>
                <button onclick="editBook(${index})">Editar</button>
                <button onclick="deleteBook(${index})">Eliminar</button>
            </td>
        `;
        tableBody.appendChild(row);
    });
};

const saveBook = () => {
    const title = document.getElementById('book-title').value;
    const author = document.getElementById('book-author').value;
    const year = document.getElementById('book-year').value;

    if (title && author && year) {
        const books = JSON.parse(localStorage.getItem(booksKey)) || [];
        books.push({ title, author, year });
        localStorage.setItem(booksKey, JSON.stringify(books));
        loadBooks();
        cancelForm();
    }
};

const editBook = (index) => {
    const books = JSON.parse(localStorage.getItem(booksKey));
    const book = books[index];

    document.getElementById('book-title').value = book.title;
    document.getElementById('book-author').value = book.author;
    document.getElementById('book-year').value = book.year;

    document.getElementById('save-book-btn').onclick = () => {
        books[index] = {
            title: document.getElementById('book-title').value,
            author: document.getElementById('book-author').value,
            year: document.getElementById('book-year').value,
        };
        localStorage.setItem(booksKey, JSON.stringify(books));
        loadBooks();
        cancelForm();
    };

    document.getElementById('book-form').classList.remove('hidden');
};

const deleteBook = (index) => {
    const books = JSON.parse(localStorage.getItem(booksKey));
    books.splice(index, 1);
    localStorage.setItem(booksKey, JSON.stringify(books));
    loadBooks();
};

const cancelForm = () => {
    document.getElementById('book-form').classList.add('hidden');
    document.getElementById('book-title').value = '';
    document.getElementById('book-author').value = '';
    document.getElementById('book-year').value = '';
};

// Login
document.getElementById('login-form').addEventListener('submit', (e) => {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    if (username === 'admin' && password === '1234') {
        window.location.href = 'dashboard.html';
    } else {
        document.getElementById('error-message').classList.remove('hidden');
    }
});

// Mostrar formulario de agregar libro
document.getElementById('add-book-btn').addEventListener('click', () => {
    document.getElementById('book-form').classList.remove('hidden');
});

// Inicializar CRUD
loadBooks();

// Función para cancelar el formulario
document.getElementById('cancel-btn').addEventListener('click', cancelForm);
