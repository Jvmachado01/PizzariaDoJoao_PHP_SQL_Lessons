<?php
    include_once("templates/header.php");
?>
    <div id="main-container">
        <div class="container">
            <div class="row">
                <div class="col md-12">
                    <h2>Gerenciar pedidos:</h2>
                    <div class="col-md-12 table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"><span>Pedido</span>#</th>
                                    <th scope="col">Borda</th>
                                    <th scope="col">Massa</th>
                                    <th scope="col">Sabores</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Ações</th>                                   
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1</td>
                                    <td>Cheddar</td>
                                    <td>Temperada</td>
                                    <td>4 Queijos</td>
                                    <td>
                                        <form action="process/orders.php" method="POST" class="form-group update-form">
                                            <input type="hidden" name="type" value="update">
                                            <input type="hidden" name="id" value="1">
                                            <select name="" class="form-control status-input">
                                                <option value="">Entrega</option>
                                            </select>
                                            <button class="update-btn" type="submit">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>

                                        </form>
                                    </td>
                                    <td>
                                        <form action="process/orders.php" method="POST">
                                        <input type="hidden" name="type" value="deltte">
                                        <input type="hidden" name="id" value="1">
                                            <button type="submit" class="delete-btn">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    include_once("templates/footer.php");
?>