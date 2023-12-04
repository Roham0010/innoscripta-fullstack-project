function Home() {
  return (
    <div class="containr-fluid p-0">
      <div className="row home-image vh-100 justify-content-center">
        <div class="col-md-6 py-200p text-center text-white">
          <h1 class="display-4">Readify</h1>
          <h4 class="">The world is full of stories. Find yours here</h4>
          <p class="lead">
            We invite you to explore the diversity and richness of human
            experiences through reading.
          </p>
          <form action="/articles" class="d-flex justify-content-center">
            <input
              type="text"
              class="form-control"
              placeholder="Search for articles"
              aria-label="Search"
              name="search"
            />
            <button type="submit" class="btn btn-primary mx-2">
              Search
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}

export default Home;
