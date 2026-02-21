async function loadDashboard()
{
    try {
        const response = await fetch("./php/dashboard.php");
        const data = await response.json();

        document.getElementById("totalRooms").innerText = data.totalRooms;
        document.getElementById("occupiedRooms").innerText = data.occupiedRooms;
        document.getElementById("availableRooms").innerText = data.availableRooms;
        document.getElementById("reservationCount").innerText = data.reservations;
    }

    catch(error)
    {
        console.error("Error loading dashboard:" , error);
    }
}

// loadDashboard();

async function loadRecentReservations() {
  try {
    const res = await fetch("./php/recent_reservations.php");
    const rows = await res.json();

    const tbody = document.getElementById("recentReservationsBody");
    tbody.innerHTML = "";

    if (!rows || rows.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td class="px-4 py-3 text-gray-500" colspan="5">No reservations yet.</td>
        </tr>
      `;
      return;
    }

    rows.forEach(r => {
      tbody.innerHTML += `
        <tr class="border-b hover:bg-gray-50 transition">
          <td class="px-4 py-3">${escapeHtml(r.full_name)}</td>
          <td class="px-4 py-3">${escapeHtml(String(r.room_number))}</td>
          <td class="px-4 py-3">${escapeHtml(r.check_in)}</td>
          <td class="px-4 py-3">${escapeHtml(r.check_out)}</td>
          <td class="px-4 py-3">${escapeHtml(r.status)}</td>
        </tr>
      `;
    });

  } catch (err) {
    console.error("Error loading recent reservations:", err);
  }
}



function escapeHtml(str) {
  return String(str)
    .replaceAll("&", "&amp;")
    .replaceAll("<", "&lt;")
    .replaceAll(">", "&gt;")
    .replaceAll('"', "&quot;")
    .replaceAll("'", "&#039;");
}

loadDashboard();
loadRecentReservations();