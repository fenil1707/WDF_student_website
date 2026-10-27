document.addEventListener("DOMContentLoaded", function () {
  const noticeList = document.getElementById("noticeList");

  if (!noticeList) {
    return;
  }

  const searchInput = document.getElementById("searchNotice");
  const categoryFilter = document.getElementById("filterCategory");
  const sortSelect = document.getElementById("sortDate");
  const status = document.getElementById("noticeStatus");
  const prevButton = document.getElementById("prevPage");
  const nextButton = document.getElementById("nextPage");
  const pageInfo = document.getElementById("pageInfo");

  let notices = [];
  let currentPage = 1;
  const pageSize = 3;

  function renderNotices() {
    const searchText = searchInput.value.trim().toLowerCase();
    const category = categoryFilter.value;

    let filteredNotices = notices.filter(function (notice) {
      const matchesSearch =
        notice.title.toLowerCase().includes(searchText) ||
        notice.description.toLowerCase().includes(searchText);

      const matchesCategory =
        category === "All" || notice.category === category;

      return matchesSearch && matchesCategory;
    });

    filteredNotices.sort(function (a, b) {
      if (sortSelect.value === "newest") {
        return b.date.localeCompare(a.date);
      }

      return a.date.localeCompare(b.date);
    });

    const totalPages = Math.max(
      1,
      Math.ceil(filteredNotices.length / pageSize),
    );

    if (currentPage > totalPages) {
      currentPage = totalPages;
    }

    const start = (currentPage - 1) * pageSize;
    const pageNotices = filteredNotices.slice(start, start + pageSize);

    noticeList.replaceChildren();

    if (pageNotices.length === 0) {
      status.textContent =
        "No notices found. Try a different search or filter.";
    } else {
      status.textContent = filteredNotices.length + " notice(s) found.";

      pageNotices.forEach(function (notice) {
        const card = document.createElement("article");
        card.className = "notice-card";

        const categoryBadge = document.createElement("span");
        categoryBadge.className = "notice-category";
        categoryBadge.textContent = notice.category;

        const title = document.createElement("h3");
        title.textContent = notice.title;

        const date = document.createElement("p");
        date.className = "notice-date";
        date.textContent = new Date(
          notice.date + "T00:00:00",
        ).toLocaleDateString("en-IN", {
          day: "numeric",
          month: "short",
          year: "numeric",
        });

        const description = document.createElement("p");
        description.textContent = notice.description;

        card.append(categoryBadge, title, date, description);
        noticeList.appendChild(card);
      });
    }

    pageInfo.textContent = "Page " + currentPage + " of " + totalPages;
    prevButton.disabled = currentPage === 1;
    nextButton.disabled =
      currentPage === totalPages || filteredNotices.length === 0;
  }

  searchInput.addEventListener("input", function () {
    currentPage = 1;
    renderNotices();
  });

  categoryFilter.addEventListener("change", function () {
    currentPage = 1;
    renderNotices();
  });

  sortSelect.addEventListener("change", function () {
    currentPage = 1;
    renderNotices();
  });

  prevButton.addEventListener("click", function () {
    if (currentPage > 1) {
      currentPage--;
      renderNotices();
    }
  });

  nextButton.addEventListener("click", function () {
    currentPage++;
    renderNotices();
  });

  fetch("notices.json")
    .then(function (response) {
      if (!response.ok) {
        throw new Error("Could not load notices.json");
      }

      return response.json();
    })
    .then(function (data) {
      if (!Array.isArray(data)) {
        throw new Error("Notice data must be an array.");
      }

      notices = data;
      renderNotices();
    })
    .catch(function (error) {
      status.textContent =
        "Unable to load notices. Check notices.json and run the project using Live Server.";
      console.error(error);
    });
});
