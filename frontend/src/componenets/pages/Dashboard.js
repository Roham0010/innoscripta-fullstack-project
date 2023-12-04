import { useEffect, useState } from "react";
import baseAPI from "../../utils/baseAPI";
import CategoryFilter from "../common/CategoryFilter";
import SourceFilter from "../common/SourceFilter";
import { Spin } from "antd";

const Dashboard = () => {
  const [preferences, setPreferences] = useState({});
  const [searchData, setSearchData] = useState(1);

  const [categories, setCategories] = useState([]);
  const [sources, setSources] = useState([]);
  const [authors, setAuthors] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    baseAPI
      .get("articles-search-data?resources=authors,categories,sources")
      .then((res) => {
        const data = res.data;
        setSearchData(data);
      });
    setLoading(true);
    baseAPI.get(`users/get-preferences`).then((res) => {
      const data = res?.data;
      setLoading(false);
      setPreferences(data);
      setCategories(data?.categories ?? []);
      setAuthors(data?.authors ?? []);
      setSources(data?.sources ?? []);
    });
  }, []);

  const storePreferences = () => {
    const pref = { categories, authors, sources };
    baseAPI
      .post(`users/store-preferences`, {
        preferences: pref,
      })
      .then();
    setPreferences(pref);
  };

  return (
    <div class="pt-2">
      <div class="row">
        <div class="col-md-12">
          <div class="grid search">
            <div class="grid-body">
              <div className="row mb-1">
                <h4 className="p-0">Dashboard - feed preferences</h4>
              </div>
              <hr class="mt-0" />
              {loading ? (
                <div className="row align-center justify-content-center py-4">
                  <Spin />
                </div>
              ) : (
                <div>
                  <div class="row">
                    <div className="col-md-4">
                      <div className="row h5">Select sources:</div>
                      <SourceFilter
                        sourcesData={searchData?.sources}
                        sources={sources ?? []}
                        resetSearchStates={() => {}}
                        setSources={setSources}
                      />
                    </div>
                    <div className="col-md-4">
                      <div className="row h5">Selected categories:</div>
                      <div className="row text-bold">
                        <CategoryFilter
                          categoriesData={searchData?.categories}
                          categories={categories}
                          resetSearchStates={() => {}}
                          setCategories={setCategories}
                        />
                      </div>
                    </div>
                    <div className="col-md-4">
                      <div className="row h5">Selected authors:</div>
                      <div className="row text-bold">
                        <SourceFilter
                          sources={authors ?? []}
                          sourcesData={searchData?.authors}
                          resetSearchStates={() => {}}
                          setSources={setAuthors}
                        />
                      </div>
                    </div>
                  </div>
                  <div className="row">
                    <button
                      className="btn btn-primary float-right offset-10 col-md-2"
                      onClick={storePreferences}
                    >
                      {" "}
                      Save{" "}
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
