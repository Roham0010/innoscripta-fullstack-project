import { useEffect, useState } from "react";
import request from "../../utils/baseAPI";
import { useParams } from "react-router-dom";
import { Spin } from "antd";

const Article = () => {
  let { id } = useParams();
  const [article, setArticle] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    setLoading(true);
    request.get(`articles/${id}`).then((res) => {
      const data = res?.data;
      setArticle(data?.article);
      setLoading(false);
    });
  }, [id]);

  if (loading) {
    return (
      <div className="row align-center justify-content-center py-4">
        <Spin />
      </div>
    );
  }
  if (!article) {
    return (
      <div className="row align-center justify-content-center py-4">
        Not Found
      </div>
    );
  }

  const {
    title,
    category,
    author,
    description,
    keywordss,
    published_at,
    source,
    body,
  } = article;
  return (
    <div>
      <div class="p-4 p-md-5 mb-4 text-white bg-dark">
        <div class="col-md-6 px-0">
          <h1 class="display-4 fst-italic">{title}</h1>
          <p class="lead my-3">{description}</p>
          <p class="lead mb-0">
            <a
              href="#post-content"
              class="text-white fw-bold"
              onClick={(e) => e.preventDefault()}
            >
              Continue reading...
            </a>
          </p>
        </div>
      </div>

      <div class="row g-5" id="post-content">
        <div class="col-md-8">
          <article class="blog-post">
            <h2 class="blog-post-title">{title}</h2>
            <p class="blog-post-meta">
              {new Date(published_at).toLocaleDateString("en-US", {
                weekday: "long",
                year: "numeric",
                month: "long",
                day: "numeric",
              })}{" "}
              by <a href="/">{author}</a>
            </p>

            <p>{body}</p>
          </article>
        </div>

        <div class="col-md-4">
          <div class="position-sticky" style={{ top: "2rem" }}>
            <div class="p-4 mb-3 bg-light rounded">
              <h4 class="fst-italic">Keywords</h4>
              <div class="col-md-12">
                {keywordss.map((keyword) => (
                  <>
                    <span class="badge bg-secondary m-1">{keyword}</span>
                  </>
                ))}
              </div>
            </div>
            <div class="p-4 mb-3 bg-light rounded">
              <h6 class="fst-italic">Souce:</h6>
              <div class="col-md-12">{source}</div>
            </div>
            <div class="p-4 mb-3 bg-light rounded">
              <h6 class="fst-italic">Category:</h6>
              <div class="col-md-12">{category}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Article;
