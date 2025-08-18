<?php if(isset($_SESSION['message'])): ?>
                <div class="fixed top-4 right-4 z-50">
                    <div class="<?= strpos($_SESSION['message'], 'Error') === 0 ? 'bg-red-50 border-red-400 text-red-700' : 'bg-green-50 border-green-400 text-green-700' ?> rounded border px-4 py-3 mb-4 transition-all duration-300 transform hover:scale-[1.02] shadow-lg" role="alert">
                        <div class="flex items-center">
                            <div class="py-1">
                                <?php if(strpos($_SESSION['message'], 'Error') === 0): ?>
                                    <svg class="w-6 h-6 mr-2 text-red-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-6 h-6 mr-2 text-green-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium"><?= htmlspecialchars($_SESSION['message']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
<?php 
unset($_SESSION['message']);
endif; 
?>                