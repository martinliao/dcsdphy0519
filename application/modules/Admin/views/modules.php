<?php $this->load->view('header'); ?>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header clearfix">
                        <h5 class="float-left">Manage Modules</h5>
                        <div class="float-right">
                            <a href="<?php echo base_url($URI.'/create'); ?>" class="btn btn-success btn-sm"> Create</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tables-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Controller</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (count($items) > 0): foreach ($items as $item): ?>
                                    <tr>
                                        <td><?php echo $item->name; ?></td>
                                        <td><?php echo $item->controller; ?></td>
                                        <td><?php echo count(json_decode($item->permissions,0)); ?></td>
                                        <td>
                                            <form action="<?php echo base_url($URI.'/delete/' . $item->id); ?>"
                                                  method="post" onsubmit="return confirm('Are you sure?');">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?php echo base_url($URI.'/edit/' . $item->id); ?>"
                                                       class="btn btn-info"> Edit</a>
                                                    <button type="submit" class="btn btn-danger"> Delete</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No results found!</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('footer'); ?>