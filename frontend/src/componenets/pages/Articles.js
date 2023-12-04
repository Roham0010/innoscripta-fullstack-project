// import { useSearchParams } from "react-router-dom";

import { useEffect, useState } from "react";
import baseAPI from "../../utils/baseAPI";
import { useSearchParams } from "react-router-dom";
import ArticleSumary from "../article/ArticleSumary";
import DatePicker from "../common/DatePicker";
import CategoryFilter from "../common/CategoryFilter";
import SourceFilter from "../common/SourceFilter";
import { Switch } from "antd";

const Articles = () => {
  // API
  const [articles, setArticles] = useState([]);
  const [searchData, setSearchData] = useState(1);

  // Pagination
  const [page, setPage] = useState(1);
  const [currentPage, setCurrentPage] = useState(0);
  const [totalPages, setTotalPages] = useState(1);
  const [timer, setTimer] = useState(null);

  // Filters
  const [searchParams, setSearchParams] = useSearchParams();
  const [search, setSearch] = useState();
  const [categories, setCategories] = useState([]);
  const [sources, setSources] = useState([]);
  const [fromDate, setFromDate] = useState("");
  const [toDate, setToDate] = useState("");
  const [byFeed, setByFeed] = useState(false);

  /**
   * Reset the search states after each filter change to get the results again.
   */
  const resetSearchStates = () => {
    setPage(1);
    setCurrentPage(0);
    setArticles([]);
  };

  const changeWithDelay = (search) => {
    setSearch(search);
    if (timer) {
      resetSearchStates();
      clearTimeout(timer);
      setTimer(null);
    }
    setTimer(
      setTimeout(() => {
        setSearchParams({ search });
      }, 1000)
    );
  };

  useEffect(() => {
    baseAPI
      .get("articles-search-data?resources=categories,sources")
      .then((res) => {
        const data = res.data;
        setSearchData(data);
      });
  }, []);

  useEffect(() => {
    const scrollCheck = () => {
      if (
        Math.round(window.innerHeight + document.documentElement.scrollTop) ===
        document.documentElement.offsetHeight
      ) {
        if (page + 1 <= totalPages) {
          setPage(page + 1);
        }
      }
    };

    setSearch(searchParams.get("search") ?? "");
    const searchLength = searchParams.get("search").length;
    if ((searchLength >= 2 || searchLength === 0) && page !== currentPage) {
      baseAPI
        .get(
          `articles?page=${page}&search=${searchParams.get(
            "search"
          )}&categories=${categories.join(",")}&sources=${sources.join(
            ","
          )}&from=${fromDate}&to=${toDate}&byFeed=${byFeed}`
        )
        .then((res) => {
          const data = res?.data;
          setTotalPages(data?.last_page);
          setPage(data?.current_page);
          setCurrentPage(data?.current_page);
          setArticles([...articles, ...data?.data]);
        });
    }
    window.addEventListener("scroll", scrollCheck);
    return () => {
      window.removeEventListener("scroll", scrollCheck);
    };
  }, [
    searchParams,
    totalPages,
    page,
    categories,
    fromDate,
    toDate,
    sources,
    articles,
    currentPage,
    byFeed,
  ]);

  if (!articles) {
    return (
      <div className="row align-center justify-content-center py-4">
        Not Found
      </div>
    );
  }
  return (
    <>
      <div class="container pt-2">
        <div class="row">
          <div class="col-md-12">
            <div class="grid search">
              <div class="grid-body">
                <div class="row">
                  <div class="col-md-3">
                    <h2 class="grid-title">
                      <i class="bi bi-filter"></i> Filters
                    </h2>
                    <hr />
                    <div>
                      <h6 className="d-inline">By Feed:</h6>
                      <Switch
                        checked={byFeed}
                        className="d-inline mx-2"
                        onChange={(e) => {
                          setByFeed(e);
                          setCategories([]);
                          setSources([]);
                          setFromDate("");
                          setToDate("");
                          resetSearchStates();
                        }}
                        checkedChildren="Feed"
                        unCheckedChildren="All"
                      />
                    </div>
                    <hr />
                    <h6>By Date:</h6>
                    <DatePicker
                      date={fromDate}
                      setDate={(e) => {
                        setFromDate(e);
                        setByFeed(false);
                        resetSearchStates();
                      }}
                      label="From"
                    />
                    <DatePicker
                      date={toDate}
                      setDate={(e) => {
                        setToDate(e);
                        setByFeed(false);
                        resetSearchStates();
                      }}
                      label="To"
                    />
                    <hr />
                    <h6>By Category:</h6>
                    <CategoryFilter
                      categoriesData={searchData?.categories}
                      categories={categories}
                      resetSearchStates={resetSearchStates}
                      setCategories={(e) => {
                        setCategories(e);
                        setByFeed(false);
                      }}
                    />
                    <hr />
                    <h6>By Source:</h6>
                    <SourceFilter
                      sourcesData={searchData?.sources}
                      resetSearchStates={resetSearchStates}
                      setSources={(e) => {
                        setSources(e);
                        setByFeed(false);
                      }}
                    />
                  </div>
                  <div class="col-md-9">
                    <h2>
                      <i class="bi bi-file-o"></i> Result
                    </h2>
                    <hr />
                    <div class="input-group">
                      <input
                        type="text"
                        class="form-control"
                        aria-describedby="button-addon2"
                        value={search}
                        onChange={(e) => {
                          changeWithDelay(e.target.value);
                          setByFeed(false);
                        }}
                      />
                      <button
                        class="btn btn-primary py-2"
                        type="button"
                        id="button-addon2"
                        onClick={() => {
                          resetSearchStates();
                          setByFeed(false);
                        }}
                      >
                        <i class="bi bi-search"></i>
                      </button>
                    </div>
                    <p>Showing all results matching {search}</p>

                    <div class="padding"></div>

                    <div class="table-responsive">
                      <table class="table table-hover">
                        <tbody>
                          {articles.map((article) => (
                            <ArticleSumary article={article} />
                          ))}
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default Articles;
