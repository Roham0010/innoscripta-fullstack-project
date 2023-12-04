import { Link } from "react-router-dom";

const ArticleSumary = (article) => {
  let { id, title, author, published_at, source, description } =
    article.article;
  return (
    <tr>
      <td class="number text-center">
        <small>#{id}</small>
      </td>
      <td class="product">
        <h6>
          <Link to={`/articles/${id}`} className="text-decoration-none">
            {" "}
            {title}
          </Link>
        </h6>

        <p>By {author}</p>
        <small className="test-sm">{description}</small>
      </td>
      {/* <td class="text-right">{category}</td> */}
      <td class="text-right">
        <small>{source}</small>
        <br />
        <br />
        <small>Publishe At:</small>
        <br />
        <small>{published_at.split(" ")[0]}</small>
      </td>
    </tr>
  );
};

export default ArticleSumary;
