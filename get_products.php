<?php
require_once 'includes/db.inc.php';
require_once './includes/functions.php';
include './includes/select_products.php';

$content = '';
$content .= "
<thead>
<tr>
<th class='date'>Date</th>
<th class='prd_name'>Product name</th>
<th>SKU</th>
<th>Price</th>
<th>Feature Image</th>
<th class='gallery_name'>Gallery</th>
<th >Categories</th>
<th class='tag_name'>Tags</th>
<th id='action_box' class='action_box'>
        <span>Action</span>
        <div class='box_delete_buttons'>
            <a  class='delete_buttons'>
                <i class='trash icon'></i>
            </a>
        </div>
    </th>
</tr>
</thead>
";
if (count($results) > 0) {
foreach ($results as $row) {
    $product_id = $row['id'];
    $galleryImages = $row['gallery_images'] ?? ''; 
    $galleryImagesArray =  explode(', ', $galleryImages);
    $imageSrc = $row['featured_image'];
    
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $row['date']);
    if ($date !== false) {
        $date = $date->format('d/m/Y');
    } else {
        $date = 'error date'; 
    }

    $content .= '<tbody id="productTableBody">';
    $content .= '<tr  data-id="' . $row['id'] . '">';
    $content .= '<td>' . htmlspecialchars($date) . '</td>';
    $content .= '<td class="product_name">' . htmlspecialchars(htmlspecialchars_decode($row['product_name'] ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
    $content .= '<td class="sku">' . htmlspecialchars($row['sku']) . '</td>';
    $content .= '<td>$<span class="price">' . htmlspecialchars(rtrim(rtrim($row['price'], '0'), '.') ?? '') . '</span></td>';
    $content .= '<td class="featured_image">';


    if (filter_var($imageSrc, FILTER_VALIDATE_URL)) {
        $content .= '<img src="' . $imageSrc. '">';
    } else if(!empty($imageSrc)) {
        $content .= '<img src="uploads/' . $imageSrc . '">';
    }else{
        $content .='';
    }
    $content .= '</td>';
    
    if (!empty($galleryImages)) {
        $content .= '<td class="gallery"><div class="gallery-container">';
        foreach ($galleryImagesArray as $image) {
            $content .= '<img height="30" src="uploads/' . $image . '">';
        }
        $content .= '</div></td>';
    }else{
       $content .= '<td></td>';
    }
   
    $categorySelected = "SELECT p.name_ FROM product_property pp
    JOIN property p ON pp.property_id = p.id
    WHERE pp.product_id = :product_id AND p.type_ = 'category'";
    $stmt = $pdo->prepare($categorySelected);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $categoriesse = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $categoryNames = [];
    foreach ($categoriesse as $category) {
        $categoryNames[] = $category['name_'];
    }
    $categoryList = implode(', ', $categoryNames);

    $content .= '<td class="category">' . htmlspecialchars($categoryList) . '</td>';

    $tagSelected = "SELECT p.name_ FROM product_property pp
                    JOIN property p ON pp.property_id = p.id
                    WHERE pp.product_id = :product_id AND p.type_ = 'tag'";
        $stmt = $pdo->prepare($tagSelected);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $tagsse = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $tagNames = [];
    foreach ($tagsse as $tag) {
        $tagNames[] = $tag['name_'];
    }
    $tagList = implode(', ', $tagNames);

    $content .= '<td class="tag">' . htmlspecialchars($tagList) . '</td>';
    $content .= '<td class="box_action"><button value='.$row['id'].'
                  class="edit_button" data-id='. $row['id'].'><i class="edit icon"></i></button>';
    $content .= '<a class="delete_button" data-id="' . $row['id'] . '">
    <i class="trash icon"></i></a></td>';
    $content .= '</tr>';
    $content .= '</tbody>'; 
}

}else{
    $content .= '
        <tr>
            <td colspan="9">Product not found</td>
        </tr>
    ';
}

$pagination = '';
$inputpage = '';

$total_pages = ceil($total_records / $per_page_record);

$inputpage .= '
<input type="hidden" id="currentPages" value='.$page.'> 
';
$pagination .= '    

<div id="paginationBox" class="pagination_box">
<div class="ui pagination menu">
';
if ($page > 1) {
    $pagination .= '<a class="item pagination-link" data-page="' . ($page - 1) . '">
    <i class="arrow left icon"></i>
    </a>';
} else {
    $pagination .= '<a class="item disabled">
    <i class="arrow left icon"></i>
    </a>';
}

for ($i = 1; $i <= $total_pages; $i++) {
    $active_class = ($i == $page) ? 'active' : '';
    $pagination .= '<a class="item pagination-link ' . $active_class . '" data-page="' . $i . '">' . $i . '</a>';
}

if ($page < $total_pages) {
    $pagination .= '<a class="item pagination-link" data-page="' . ($page + 1) . '">
    <i class="arrow right icon"></i>
    </a>';
} else {
    $pagination .= '<a class="item disabled">
    <i class="arrow right icon"></i>
    </a>
   ';
}
$pagination .= '
   </div>
</div>
';

echo json_encode([
    'content' => $content,
    'pagination' => $pagination,
    "totalProducts" => $total_records,  
    "page" => $page,
    "inputpage" => $inputpage
]);
?>


