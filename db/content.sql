insert into t_chapter values
(1, 'First chapter', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit mauris ac porttitor accumsan. Nunc vitae pulvinar odio, auctor interdum dolor. Aenean sodales dui quis metus iaculis, hendrerit vulputate lorem vestibulum. Suspendisse pulvinar, purus at euismod semper, nulla orci pulvinar massa, ac placerat nisi urna eu tellus. Fusce dapibus rutrum diam et dictum. Sed tellus ipsum, ullamcorper at consectetur vitae, gravida vel sem. Vestibulum pellentesque tortor et elit posuere vulputate. Sed et volutpat nunc. Praesent nec accumsan nisi, in hendrerit nibh. In ipsum mi, fermentum et eleifend eget, eleifend vitae libero. Phasellus in magna tempor diam consequat posuere eu eget urna. Fusce varius nulla dolor, vel semper dui accumsan vitae. Sed eget risus neque.', true);
insert into t_chapter values
(2, 'Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut hendrerit mauris ac porttitor accumsan. Nunc vitae pulvinar odio, auctor interdum dolor. Aenean sodales dui quis metus iaculis, hendrerit vulputate lorem vestibulum. Suspendisse pulvinar, purus at euismod semper, nulla orci pulvinar massa, ac placerat nisi urna eu tellus. Fusce dapibus rutrum diam et dictum. Sed tellus ipsum, ullamcorper at consectetur vitae, gravida vel sem. Vestibulum pellentesque tortor et elit posuere vulputate. Sed et volutpat nunc. Praesent nec accumsan nisi, in hendrerit nibh. In ipsum mi, fermentum et eleifend eget, eleifend vitae libero. Phasellus in magna tempor diam consequat posuere eu eget urna. Fusce varius nulla dolor, vel semper dui accumsan vitae. Sed eget risus neque.',true);
insert into t_chapter values
(3, 'Lorem ipsum in french', "J’en dis autant de ceux qui, par mollesse d’esprit, c’est-à-dire par la crainte de la peine et de la douleur, manquent aux devoirs de la vie. Et il est très facile de rendre raison de ce que j’avance. Car, lorsque nous sommes tout à fait libres, et que rien ne nous empêche de faire ce qui peut nous donner le plus de plaisir, nous pouvons nous livrer entièrement à la volupté et chasser toute sorte de douleur ; mais, dans les temps destinés aux devoirs de la société ou à la nécessité des affaires, souvent il faut faire divorce avec la volupté, et ne se point refuser à la peine. La règle que suit en cela un homme sage, c’est de renoncer à de légères voluptés pour en avoir de plus grandes, et de savoir supporter des douleurs légères pour en éviter de plus fâcheuses.", false);

/* raw password is 'john' */
insert into t_user values
(1, 'JohnDoe', '$2y$13$F9v8pl5u5WMrCorP9MLyJeyIsOLj.0/xqKd/hqa5440kyeB7FQ8te', 'YcM=A$nsYzkyeDVjEUa7W9K', 'ROLE_USER');
/* raw password is 'jane' */
insert into t_user values
(2, 'JaneDoe', '$2y$13$qOvvtnceX.TjmiFn4c4vFe.hYlIVXHSPHfInEG21D99QZ6/LM70xa', 'dhMTBkzwDKxnD;4KNs,4ENy', 'ROLE_USER');
/* raw password is '@dm1n' */
insert into t_user values
(3, 'admin', '$2y$13$A8MQM2ZNOi99EW.ML7srhOJsCaybSbexAj/0yXrJs4gQ/2BqMMW2K', 'EDDsl&fBCJB|a5XUtAlnQN8', 'ROLE_ADMIN');

insert into t_comment values
(1, '2017-03-25 15:00:00' , 'Great! Keep up the good work.', 2,1,0);
insert into t_comment values
(2, '2017-04-14 15:55:00', "Thank you, I'll try my best.", 2,2,0);
insert into t_comment values
(3, '2017-04-15 10:20:52', "Awesome blog you should have a poulitzer", 2,3,2);
insert into t_comment values
(4, '2017-04-15 11:15:47', "Don't exagerate ! But thx !", 2,2,3);
insert into t_comment values
(5, '2017-04-16 00:14:18', "I agree it's so great !", 2,1,3);
insert into t_comment values
(6, '2017-04-16 12:32:20', "Best of the chapters I love it !", 2,1,2);
insert into t_comment values
(7, '2017-04-16 17:28:14', "Thanks !", 2,2,6);
insert into t_comment values
(8, '2017-04-17 02:17:18', "Bravo !", 2,1,0);
insert into t_comment values
(9, '2017-04-17 12:32:20', "J'ai adoré génial", 2,1,8);
insert into t_comment values
(10, '2017-04-17 22:28:14', "Moi aussi continue comme ça !", 2,3,8);
