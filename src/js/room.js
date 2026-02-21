async function createRoom() {
    
    let data = {
        roomNumber: document.getElementById("roomNumber").value,
        doubleBed: document.getElementById("doubleBed").value,
        singleBed: document.getElementById("singleBed").value,
        price: document.getElementById("price").value,
        category: document.getElementById("category").value,
        status: document.getElementById("status").value
    };

    console.log("FETCH VERSION I RI");
    let response = await fetch("./php/create_room.php", {
        method: "POST",
        headers: { "Content-Type": "application/json"},
        body: JSON.stringify(data)

    });

    let result = await response.json();
    alert(result.message);

    loadRooms();
}



async function loadRooms() {
    
    let response = await fetch("./php/get_rooms.php");
    let rooms = await response.json();

    let container = document.getElementById("roomList");
    container.innerHTML = "";

    rooms.forEach(room => {
        let card = `
        <div class="bg-white shadow p-6 rounded-xl border w-full max-w-sm">
            <h3 class="text-xl font-bold mb-2">Room ${room.room_number}</h3> 
            <p><strong>Double Bed:</strong> ${room.double_bed}</p>
            <p><strong>Single Bed:</strong> ${room.single_bed}</p>
            <p><strong>Price:</strong> ${room.price} €</p>
            <p><strong>Category:</strong> ${room.category}</p>
            <p><strong>Status:</strong> ${room.status}</p>
        </div>
        `;

        container.innerHTML += card;
    
    });
}

loadRooms();






async function editRoom(id) {
    let res = await fetch("./php/get_room.php?id=" + id);
    let room = await res.json();

    document.getElementById("editId").value = id;
    document.getElementById("editRoomNumber").value = room.room_number;
    document.getElementById("editDoubleBed").value = room.double_bed;
    document.getElementById("editSingleBed").value = room.single_bed;
    document.getElementById("editPrice").value = room.price;
    document.getElementById("editCategory").value = room.category;
    document.getElementById("editStatus").value = room.status;

    document.getElementById("editModal").classList.remove("hidden");
    document.getElementById("editModal").classList.add("flex");
}


function closeEditModal() {
    const modal = document.getElementById("editModal");
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

async function updateRoom() {
    let data = {
        id: document.getElementById("editId").value,
        room_number: document.getElementById("editRoomNumber").value,
        double_bed: document.getElementById("editDoubleBed").value,
        single_bed: document.getElementById("editSingleBed").value,
        price: document.getElementById("editPrice").value,
        category: document.getElementById("editCategory").value,
        status: document.getElementById("editStatus").value
    };

    let res = await fetch("./php/update_room.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    });

    let response = await res.json();

    if (response.success) {
        closeEditModal();
        loadRooms();
    } else {
        alert("Error updating room");
    }
}
